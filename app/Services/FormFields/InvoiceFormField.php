<?php

namespace App\Services\FormFields;

class InvoiceFormField extends BaseFormField
{
    public static function generateFields($invoice = null): array
    {
        if ($invoice) {
            return [
                new BaseFormField('product_id', 'Product ID', 'text', $invoice->product_id),
                new BaseFormField('tripay_reference', 'Tripay Reference', 'text', $invoice->tripay_reference),
                new BaseFormField('buyer_email', 'Buyer Email', 'number', $invoice->buyer_email),
                new BaseFormField('buyer_phone', 'Buyer Phone', 'text', $invoice->buyer_phone),
                new BaseFormField('raw_response', 'Raw Response', 'text', $invoice->raw_response),
            ];
        }
        return [
            new BaseFormField('product_id', 'Product ID', 'text'),
            new BaseFormField('tripay_reference', 'Tripay Reference', 'text'),
            new BaseFormField('buyer_email', 'Buyer Email', 'number'),
            new BaseFormField('buyer_phone', 'Buyer Phone', 'text'),
            new BaseFormField('raw_response', 'Raw Response', 'text'),
        ];
    }
}
