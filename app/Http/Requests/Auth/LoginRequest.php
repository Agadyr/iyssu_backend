<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Определяет, может ли пользователь отправлять этот запрос.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Правила валидации.
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255', 'exists:users,email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Сообщения об ошибках.
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Email обязателен.',
            'email.email' => 'Введите корректный email.',
            'email.max' => 'Email не может быть длиннее 255 символов.',
            'email.exists' => 'Пользователь с таким email не найден.',

            'password.required' => 'Пароль обязателен.',
            'password.string' => 'Пароль должен быть строкой.',
        ];
    }
}
