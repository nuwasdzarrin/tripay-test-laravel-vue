<?php

namespace App\Services\Shops;

use App\Exceptions\ShopException;
use App\Repositories\Invoices\InvoiceRepositoryInterface;
use App\Repositories\Products\ProductRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ShopService
{
    protected InvoiceRepositoryInterface $invoiceRepository;
    protected ProductRepositoryInterface $productRepository;
    protected $tripayUrl;
    protected $apiKey;

    public function __construct(InvoiceRepositoryInterface $invoiceRepository, ProductRepositoryInterface $productRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
        $this->productRepository = $productRepository;
        $this->tripayUrl = config('tripay.url');
        $this->apiKey = config('tripay.api_key');
    }

    public function buyAProduct($data): \App\Models\Invoice
    {
        $user = Auth::user();
        $invoiceNumber = $this->generateInvoiceNumber();
        $products = $this->productRepository->getProductByIdCollection($data['product_id']);
        $triPayResponse = $this->createTripayTransaction($invoiceNumber, $products, $data['payment_code'], $user);
        if ($triPayResponse['success']){
            try {
                return $this->invoiceRepository->create([
                    'product_id' => $data['product_id'],
                    'tripay_reference' => $triPayResponse['response_data']['data']['reference'],
                    'buyer_email' => $user->email,
                    'buyer_phone' => $user->phone_number,
                    'raw_response' => json_encode($triPayResponse['response_data']['data']),
                ]);
            } catch (\Exception $e) {
                throw new ShopException($e->getMessage());
            }
        }
        throw new ShopException($triPayResponse['error']);
    }

    protected function generateInvoiceNumber()
    {
        $currentDate = now()->format('YmdHis');
        $latestTransaction = $this->invoiceRepository->getLatestInvoice();
        $orderNumber = $latestTransaction ? intval(substr($latestTransaction->tripay_reference, -4)) + 1 : 1;
        $orderNumberFormatted = str_pad($orderNumber, 4, '0', STR_PAD_LEFT);
        return $currentDate . '-' . $orderNumberFormatted;
    }

    protected function generateTripaySignature($merchantRef, $amount)
    {
        $privateKey   = config('tripay.private_key');
        $merchantCode = config('tripay.merchant_id');
        return hash_hmac('sha256', $merchantCode.$merchantRef.$amount, $privateKey);
    }

    protected function createTripayTransaction($invoiceNumber, $products, $paymentCode, $user)
    {
        $amount = 0;
        $order_items = [];
        foreach ($products as $product) {
            $amount += $product['price'];
            $order_items[] = [
                'sku'         => $product['sku'],
                'name'        => $product['name'],
                'price'       => $product['price'],
                'quantity'    => 1,
            ];
        }
        $tripaySignature = $this->generateTripaySignature($invoiceNumber, $amount);
        $data = [
            'method'         => $paymentCode,
            'merchant_ref'   => $invoiceNumber,
            'amount'         => $amount,
            'customer_name'  => $user['name'],
            'customer_email' => $user['email'],
            'customer_phone' => $user['phone_number'],
            'order_items'    => $order_items,
            'return_url'   => '',
            'expired_time' => (time() + (24 * 60 * 60)), // 24 jam
            'signature'    => $tripaySignature
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->post($this->tripayUrl . 'transaction/create', $data);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Data successfully sent to third party API.',
                    'response_data' => $response->json()
                ];
            } elseif ($response->clientError()) {
                return [
                    'success' => false,
                    'message' => 'There was a client error.',
                    'error' => $response->body(),
                ];
            } elseif ($response->serverError()) {
                return [
                    'success' => false,
                    'message' => 'Server error on tripay',
                    'error' => $response->body(),
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Fail to sending data',
                'error' => $e->getMessage(),
            ];
        }
    }

    function getTripayPaymentMethod()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->tripayUrl . 'merchant/payment-channel');

            if ($response->successful()) {
                return $response->json();
            } elseif ($response->clientError()) {
                throw new ShopException($response->body());
            } elseif ($response->serverError()) {
                throw new ShopException($response->body());
            }
        } catch (\Exception $e) {
            throw new ShopException($e->getMessage());
        }

    }
}
