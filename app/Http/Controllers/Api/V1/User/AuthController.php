<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\OtpSetRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Service\Auth\AuthService;
use App\Service\Auth\PasswordService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService,
        private readonly PasswordService $passwordService
    ) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $this->authService->register($request->validated());
        return response()->json($data, 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $this->authService->login($request->validated());
        return response()->json($data);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();

        return response()->json(['message' => 'Logged out'])
            ->header('Clear-Site-Data', '"cookies"');
    }




    public function sendConfirmationOtp(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $this->authService->sendOtp($request->only('email'), 'email_verification');
        return response()->json(['message' => 'OTP отправлен на вашу почту']);
    }

    public function verifyOtpEmail(OtpSetRequest $request): JsonResponse
    {
        $user = $this->authService->verifyOtp($request->validated());
        return response()->json([
            'user' => $user,
            'message' => 'Почта подтверждена'
        ]);
    }

    public function sendResetOtp(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $this->authService->sendOtp($request->only('email'), 'password_reset');
        return response()->json(['message' => 'OTP отправлен на вашу почту']);
    }

    public function verifyOtpReset(OtpSetRequest $request): JsonResponse
    {
        try {
            $data = $this->passwordService->verifyOtpForReset($request->validated());
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong'], $e->getCode());
        }
    }


    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        try {
            $this->passwordService->resetPassword($request->validated());
            return response()->json(['message' => 'Пароль успешно изменён. Авторизуйтесь снова.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong'], $e->getCode());
        }
    }
}
