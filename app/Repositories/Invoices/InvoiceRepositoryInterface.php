<?php

namespace App\Repositories\Invoices;

use App\Models\Invoice;

interface InvoiceRepositoryInterface
{
    public function getLatestInvoice();
    public function create(array $data): Invoice;
}
