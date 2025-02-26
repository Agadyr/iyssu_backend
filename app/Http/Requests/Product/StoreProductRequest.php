<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'brand' => 'required|string|max:255',
            'quantity_ml' => 'required|integer|min:1',
            'is_new' => 'boolean',
            'category_id' => 'required|exists:categories,id',
            'image_url' => 'array',
            'image_url.*' => 'string|url',
            'volume_options' => 'array',
            'volume_options.*' => 'integer|min:1',
            'scent' => 'array',
            'scent.*' => 'string',
            'scent_type' => 'array',
            'scent_type.*' => 'string',
            'rating' => 'numeric|min:0|max:5',
            'discount' => 'integer|min:0',
        ];
    }
}
