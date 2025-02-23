<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Разрешаем всем пользователям использовать этот запрос
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users,email'],
            'token' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email обязателен.',
            'email.email' => 'Неверный формат email.',
            'email.exists' => 'Пользователь с таким email не найден.',
            'token.required' => 'Токен обязателен.',
            'password.required' => 'Пароль обязателен.',
            'password.min' => 'Пароль должен содержать минимум 8 символов.',
        ];
    }
}
