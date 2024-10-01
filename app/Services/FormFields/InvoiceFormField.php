<?php

namespace App\Services\FormFields;

class InvoiceFormField extends BaseFormField
{
    public static function generateFields($invoice = [], $options = []): array
    {
        return [
            new BaseFormField('product_id', 'Product ID', 'select',
                $invoice['product_id'] ?? null, $options['products'] ?? [], true,
                'product_name'),
            new BaseFormField('tripay_reference', 'Tripay Reference', 'text',
                $invoice['tripay_reference'] ?? null),
            new BaseFormField('buyer_email', 'Buyer Email', 'email',
                $invoice['buyer_email'] ?? null),
            new BaseFormField('buyer_phone', 'Buyer Phone', 'tel',
                $invoice['buyer_phone'] ?? null),
            new BaseFormField('raw_response', 'Raw Response', 'text',
                $invoice['raw_response'] ?? null),
        ];
    }
}
