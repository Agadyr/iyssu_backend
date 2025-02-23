<?php

namespace App\Service\Auth;

use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PasswordService extends AuthService
{
    private TokenService $tokenService;
    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    public function verifyOtpForReset(array $data): JsonResponse
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return response()->json(['message' => 'Пользователь не найден'], 404);
        }

        $otp = OtpCode::where([
            'user_id' => $user->id,
            'code' => $data['code'],
            'type' => 'password_reset'
        ])
        ->where('expires_at', '>', now())
        ->first();

        if (!$otp) {
            return response()->json(['message' => 'Неверный или просроченный OTP'], 400);
        }

        OtpCode::where('id', $otp->id)->delete();

        $token = $this->tokenService->createToken($user, 'password-reset', 60);

        return response()->json([
            'message' => 'OTP подтвержден. Используйте токен для смены пароля.',
            'token' => $token,
        ]);
    }

    public function resetPassword(array $data): JsonResponse
    {
        $user = User::where('email', $data['email'])->firstOrFail();

        if (!$this->tokenService->verifyToken($user, $data['token'], 'password-reset')) {
            return response()->json(['message' => 'Недействительный токен'], 400);
        }

        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        return response()->json([
            'message' => 'Пароль успешно изменён. Авторизуйтесь снова.',
        ], 200);
    }

}
