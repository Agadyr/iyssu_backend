<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'query' => 'nullable|string|min:2',
            'scent' => 'nullable|string|min:2',
            'price_min' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric|min:0',
            'brand' => 'nullable|string',
            'category_id' => 'nullable|integer|exists:categories,id',
            'rating' => 'nullable|numeric|min:1|max:5',
            'discount' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
            'gender' => 'nullable|in:man,women,uni',
            'sort' => 'nullable|in:price_asc,price_desc,rating_desc',
        ];
    }

    public function messages(): array
    {
        return [
            'query.string' => 'Поисковый запрос должен быть строкой.',
            'query.min' => 'Поисковый запрос должен содержать минимум 2 символа.',

            'scent.string' => 'Аромат должен быть строкой.',
            'scent.min' => 'Аромат должен содержать минимум 2 символа.',

            'price_min.numeric' => 'Минимальная цена должна быть числом.',
            'price_min.min' => 'Минимальная цена не может быть отрицательной.',

            'price_max.numeric' => 'Максимальная цена должна быть числом.',
            'price_max.min' => 'Максимальная цена не может быть отрицательной.',

            'brand.string' => 'Бренд должен быть строкой.',

            'category_id.integer' => 'ID категории должен быть целым числом.',
            'category_id.exists' => 'Выбранная категория не существует.',

            'rating.numeric' => 'Рейтинг должен быть числом.',
            'rating.min' => 'Рейтинг не может быть меньше 1.',
            'rating.max' => 'Рейтинг не может быть больше 5.',

            'discount.boolean' => 'Поле "скидка" должно быть true или false.',
            'is_new.boolean' => 'Поле "новинка" должно быть true или false.',
            'gender.in' => 'Поле "Пол" должно быть одним из: man, women, uni.',

            'sort.in' => 'Поле сортировки должно быть одним из: price_asc, price_desc, rating_desc.',
        ];
    }
}
