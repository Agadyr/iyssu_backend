<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class OtpSetRequest extends FormRequest
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
            'code' => ['required', 'string', 'min:4', 'max:6'],
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

            'code.required' => 'Код обязателен.',
            'code.string' => 'Код должен быть строкой.',
            'code.min' => 'Код должен содержать минимум 4 символа.',
            'code.max' => 'Код не может быть длиннее 6 символов.',
        ];
    }
}
