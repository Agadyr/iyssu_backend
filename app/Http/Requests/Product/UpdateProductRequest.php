<?php

namespace App\Http\Requests\Product;

use Illuminate\Contracts\Validation\ValidationRule;

class UpdateProductRequest extends StoreProductRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'brand' => 'sometimes|string|max:255',
            'quantity_ml' => 'sometimes|integer|min:1',
            'is_new' => 'sometimes|boolean',
            'category_id' => 'sometimes|exists:categories,id',
            'image_url' => 'sometimes|array',
            'volume_options' => 'sometimes|array',
            'scent' => 'sometimes|array',
            'scent_type' => 'sometimes|array',
            'rating' => 'sometimes|numeric|min:0|max:5',
            'discount' => 'sometimes|integer|min:0',
        ]);
    }
}
