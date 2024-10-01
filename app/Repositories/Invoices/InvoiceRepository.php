<?php

namespace App\Repositories\Invoices;

use App\Models\Invoice;

class InvoiceRepository implements InvoiceRepositoryInterface
{
    public function getLatestInvoice()
    {
        return Invoice::query()->whereDate('created_at', now()->toDateString())
            ->orderBy('id', 'desc')
            ->first();
    }

    public function create(array $data): Invoice
    {
        return Invoice::create($data);
    }
}
