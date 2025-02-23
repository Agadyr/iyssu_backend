<?php

namespace App\Service\Auth;

use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PasswordService extends AuthService
{
    private TokenService $tokenService;

    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    /**
     * Подтверждение OTP для сброса пароля.
     *
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function verifyOtpForReset(array $data): array
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            throw new \Exception('Пользователь не найден', 404);
        }

        $otp = OtpCode::where([
            'user_id' => $user->id,
            'code' => $data['code'],
            'type' => 'password_reset'
        ])
            ->where('expires_at', '>', now())
            ->first();

        if (!$otp) {
            throw new \Exception('Неверный или просроченный OTP', 400);
        }

        $otp->delete();

        $token = $this->tokenService->createToken($user, 'password-reset', 60);

        return [
            'message' => 'OTP подтвержден. Используйте токен для смены пароля.',
            'token' => $token,
        ];
    }

    /**
     * Сброс пароля.
     *
     * @param array $data
     * @return void
     * @throws \Exception
     */
    public function resetPassword(array $data): void
    {
        $user = User::where('email', $data['email'])->firstOrFail();

        if (!$this->tokenService->verifyToken($user, $data['token'], 'password-reset')) {
            throw new \Exception('Недействительный токен', 400);
        }

        $user->update([
            'password' => Hash::make($data['password']),
        ]);
    }
}
