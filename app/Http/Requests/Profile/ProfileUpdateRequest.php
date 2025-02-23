<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProfileUpdateRequest extends FormRequest
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
        $userId = Auth::id();

        return [
            'name' => 'string|max:255',
            'email' => "email|max:255|unique:users,email,{$userId}",
            'phone' => "string|max:20|unique:users,phone,{$userId}",
            'city' => 'string|max:255|nullable',
        ];
    }
}
