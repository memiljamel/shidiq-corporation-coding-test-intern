<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'product_name' => 'required|string|min:3|max:70',
            'product_description' => 'required|string|min:3|max:120',
            'product_price_capital' => 'required|numeric|gt:0',
            'product_price_sell' => 'required|numeric|gt:0',
        ];
    }
}
