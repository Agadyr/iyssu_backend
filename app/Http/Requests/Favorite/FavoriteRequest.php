<?php

namespace App\Http\Requests\Favorite;

use App\Http\Requests\UserProduct\UserProductRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class FavoriteRequest extends UserProductRequest
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
        return array_merge(parent::rules());
    }

    public function messages(): array
    {
        return array_merge(parent::messages());
    }
}
