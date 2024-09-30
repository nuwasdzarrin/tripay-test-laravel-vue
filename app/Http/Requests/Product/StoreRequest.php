<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'sku' => 'required|string|max:255|unique:products,sku',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'reference' => 'string|max:255|nullable',
        ];
    }
}
