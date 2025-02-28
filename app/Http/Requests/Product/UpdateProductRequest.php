<?php

namespace App\Http\Requests\Product;

use Illuminate\Contracts\Validation\ValidationRule;

class UpdateProductRequest extends StoreProductRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return collect(parent::rules())
            ->mapWithKeys(fn($rule, $key) => [$key => 'sometimes|' . $rule])
            ->toArray();
    }

    public function messages(): array
    {
        $messages = parent::messages();

        return collect($messages)->mapWithKeys(function ($message, $key) {
            $newKey = str_replace('required', 'sometimes', $key);
            return [$newKey => str_replace('обязательно для заполнения', 'может быть заполнено', $message)];
        })->toArray();
    }
}
