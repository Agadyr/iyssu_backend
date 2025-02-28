<?php

namespace App\Service\Auth;

use App\Exceptions\ApiException;
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
     * @throws ApiException
     */
    public function verifyOtpForReset(array $data): array
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            throw new ApiException(404, 'Пользователь не найден');
        }

        $otp = OtpCode::where([
            'user_id' => $user->id,
            'code' => $data['code'],
            'type' => 'password_reset'
        ])
            ->where('expires_at', '>', now())
            ->first();

        if (!$otp) {
            throw new ApiException( 400, 'Неверный или просроченный OTP');
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
     * @throws ApiException
     */
    public function resetPassword(array $data): void
    {
        $user = User::where('email', $data['email'])->firstOrFail();

        if (!$this->tokenService->verifyToken($user, $data['token'], 'password-reset')) {
            throw new ApiException(400, 'Недействительный токен');
        }

        $user->update([
            'password' => Hash::make($data['password']),
        ]);
    }
}
