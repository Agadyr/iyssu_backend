<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'regex:/^\+7\d{10}$/', 'unique:users,phone'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    /**
     * Сообщения об ошибках.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Имя обязательно.',
            'name.string' => 'Имя должно быть строкой.',
            'name.max' => 'Имя не может быть длиннее 255 символов.',

            'phone.unique' => 'Этот номер уже зарегистрирован',

            'email.required' => 'Email обязателен.',
            'email.email' => 'Введите корректный email.',
            'email.max' => 'Email не может быть длиннее 255 символов.',
            'email.unique' => 'Этот email уже зарегистрирован.',

            'password.required' => 'Пароль обязателен.',
            'password.string' => 'Пароль должен быть строкой.',
            'password.min' => 'Пароль должен содержать минимум 8 символов.',
            'password.confirmed' => 'Пароли не совпадают.',
        ];
    }
}
