<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => 'numeric|exists:products,id',
            'tripay_reference' => 'string|max:255',
            'buyer_email' => 'email',
            'buyer_phone' => 'string|max:255',
            'raw_response' => 'string|nullable',
        ];
    }
}
