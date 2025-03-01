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
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|in:ml,pcs',
            'is_new' => 'boolean',
            'category_id' => 'required|exists:categories,id',
            'image_url' => 'array',
            'image_url.*' => 'string|url',
            'volume_options' => 'array',
            'volume_options.*' => 'integer|min:1',
            'scent' => 'array',
            'scent.*' => 'string',
            'rating' => 'numeric|min:0|max:5',
            'discount' => 'integer|min:0',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Поле Название обязательно для заполнения.',
            'name.string' => 'Поле Название должно быть строкой.',
            'name.max' => 'Поле Название не должно превышать 255 символов.',

            'description.required' => 'Поле Описание обязательно для заполнения.',
            'description.string' => 'Поле Описание должно быть строкой.',

            'price.required' => 'Поле Цена обязательно для заполнения.',
            'price.numeric' => 'Поле Цена должно быть числом.',
            'price.min' => 'Поле Цена не может быть отрицательной.',

            'brand.required' => 'Поле Бренд обязательно для заполнения.',
            'brand.string' => 'Поле Бренд должно быть строкой.',
            'brand.max' => 'Поле Бренд не должно превышать 255 символов.',

            'quantity_ml.required' => 'Поле Объем (мл) обязательно для заполнения.',
            'quantity_ml.integer' => 'Поле Объем (мл) должно быть целым числом.',
            'quantity_ml.min' => 'Поле Объем (мл) должно быть больше 0.',

            'is_new.boolean' => 'Поле Новинка должно быть true или false.',

            'category_id.required' => 'Поле Категория обязательно для заполнения.',
            'category_id.exists' => 'Выбранная категория не существует.',

            'image_url.array' => 'Поле Изображения должно быть массивом.',
            'image_url.*.string' => 'Каждое изображение должно быть строкой.',
            'image_url.*.url' => 'Каждое изображение должно быть валидной ссылкой.',

            'volume_options.array' => 'Поле Объемы должно быть массивом.',
            'volume_options.*.integer' => 'Каждое значение в поле Объемы должно быть целым числом.',
            'volume_options.*.min' => 'Каждое значение в поле Объемы должно быть больше 0.',

            'scent.array' => 'Поле Аромат должно быть массивом.',
            'scent.*.string' => 'Каждое значение в поле Аромат должно быть строкой.',

            'rating.numeric' => 'Поле Рейтинг должно быть числом.',
            'rating.min' => 'Поле Рейтинг не может быть меньше 0.',
            'rating.max' => 'Поле Рейтинг не может быть больше 5.',

            'discount.integer' => 'Поле Скидка должно быть целым числом.',
            'discount.min' => 'Поле Скидка не может быть отрицательной.',

            'quantity.required' => 'Поле Количество обязательно для заполнения.',
            'quantity.integer' => 'Поле Количество должно быть целым числом.',
            'quantity.min' => 'Поле Количество должно быть больше 0.',

            'unit.required' => 'Поле Единица измерения обязательно.',
            'unit.in' => 'Поле Единица измерения должно быть либо "ml", либо "pcs".',
        ];
    }
}
