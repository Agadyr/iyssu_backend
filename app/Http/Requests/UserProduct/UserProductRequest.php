<?php

namespace App\Http\Requests\UserProduct;

use Illuminate\Foundation\Http\FormRequest;

class UserProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules(): array
    {
        return [
            'product_id' => 'required|integer|exists:products,id',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'ID товара обязателен.',
            'product_id.integer' => 'ID товара должен быть числом.',
            'product_id.exists' => 'Товар с указанным ID не существует.',
        ];
    }
}
