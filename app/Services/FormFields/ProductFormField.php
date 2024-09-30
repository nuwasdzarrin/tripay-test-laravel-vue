<?php

namespace App\Services\FormFields;

class ProductFormField extends BaseFormField
{
    public static function generateFields($product = null): array
    {
        if ($product) {
            return [
                new BaseFormField('sku', 'SKU', 'text', $product->sku),
                new BaseFormField('name', 'Name', 'text', $product->name),
                new BaseFormField('price', 'Price', 'number', $product->price),
                new BaseFormField('reference', 'Reference', 'text', $product->reference),
            ];
        }
        return [
            new BaseFormField('sku', 'SKU', 'text'),
            new BaseFormField('name', 'Name', 'text'),
            new BaseFormField('price', 'Price', 'number'),
            new BaseFormField('reference', 'Reference', 'text'),
        ];
    }
}
