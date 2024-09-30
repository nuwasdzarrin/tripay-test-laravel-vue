<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
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
            'sort' => 'sometimes|string|regex:/^[a-zA-Z]+$/u|in:id,sku,name,price',
            'order' => 'sometimes|string|regex:/^[a-zA-Z]+$/u|in:asc,desc',
        ];
    }
}
