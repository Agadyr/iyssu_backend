<?php

namespace App\Service\Auth;

use App\Mail\SendOtpCode;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthService
{
    public function register(array $data): JsonResponse
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'email_verified_at' => null,
        ]);

        $token = $user->createToken('auth_token', ['*'],now()->addDay(1))->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    public function login(array $data): JsonResponse
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user || ! Hash::check($data['password'], $user->password)) {
            return response()->json(['The provided credentials are incorrect'], 403);
        }

        $token = $user->createToken('auth_token', ['*'],now()->addHours(5))->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    public function sendOtp(array $data, string $type): JsonResponse
    {
        $user = User::where('email', $data['email'])->first();

        [$otpCode, $expiresAt] = $this->generateOtp();

        $otp = OtpCode::updateOrCreate(
            ['user_id' => $user->id, 'type' => $type],
            ['code' => $otpCode, 'expires_at' => $expiresAt]
        );

        Mail::to($data['email'])->queue(new SendOtpCode($user, $otp));
        return response()->json(['message' => 'OTP отправлен на вашу почту'], 200);

    }
    public function verifyOtp(array $data): JsonResponse
    {
        $user = User::where('email', $data['email'])->first();

        if ($user->email_verified_at) {
            return response()->json(['message' => 'Этот пользователь уже потвердил свою почту'], 409);
        }

        $otp = OtpCode::where('user_id', $user->id)
            ->where('code', $data['code'])
            ->where('type', 'email_verification')
            ->where('expires_at', '>', now())
            ->first();

        if (!$otp) {
            return response()->json(['message' => 'Неверный или просроченный OTP'], 400);
        }

        $user->email_verified_at = now();
        $user->save();

        OtpCode::where('id', $otp->id)->delete();

        return response()->json([
            'user' => $user,
            'message' => 'Почта подтверждена'
        ], 200);
    }

    public function destroyTokens(): JsonResponse
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'message' => 'Logout Successful'
        ], 200);
    }

    protected function generateOtp (): array
    {
        $otpCode = rand(1000, 9999);
        $expiresAt = now()->addSeconds(180);

        return [$otpCode, $expiresAt];
    }

}
