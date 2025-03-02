<?php

namespace App\Http\Requests\CartItem;

use App\Http\Requests\UserProduct\UserProductRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class CartItemRequest extends UserProductRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'quantity' => 'required|integer|min:1',
        ]);
    }

    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'quantity.required' => 'Количество товара обязательно.',
            'quantity.integer' => 'Количество должно быть числом.',
            'quantity.min' => 'Количество товара должно быть не меньше 1.',
        ]);
    }
}
