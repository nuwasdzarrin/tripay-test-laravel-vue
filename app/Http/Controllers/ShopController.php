<?php

namespace App\Http\Controllers;

use App\Exceptions\ShopException;
use App\Http\Requests\Product\IndexRequest;
use App\Http\Requests\Shop\BuyProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ShopResource;
use App\Models\Product;
use App\Services\Shops\ShopService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    protected Product $product;
    protected ShopService $shopService;
    public function __construct(Product $product, ShopService $shopService)
    {
        $this->product = $product;
        $this->shopService = $shopService;
    }

    public function index(IndexRequest $request) {
        $products = new ProductCollection($this->product->getFiltered($request->validated()));
        return View('shops.index', ['data' => $products]);
    }

    public function getProduct(IndexRequest $request): ProductCollection
    {
        return new ProductCollection($this->product->getFiltered($request->validated()));
    }

    /**
     * @throws ShopException
     */
    public function getPaymentMethod(): ShopResource
    {
        return new ShopResource($this->shopService->getTripayPaymentMethod());
    }

    /**
     * @throws ShopException
     */
    public function buyProduct(BuyProductRequest $request): ShopResource
    {
        return new ShopResource($this->shopService->buyAProduct($request->validated()));
    }
}
