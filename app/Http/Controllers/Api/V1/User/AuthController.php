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

class AuthController extends Controller
{
    private AuthService $authService;
    private PasswordService $passwordService;

    public function __construct(AuthService $authService, PasswordService $passwordService)
    {
        $this->authService = $authService;
        $this->passwordService = $passwordService;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        return $this->authService->register($request->validated());
    }

    public function sendConfirmationOtp(Request $request): JsonResponse
    {
        $validated = $request->validate(['email' => 'required|email|exists:users,email']);

        return $this->authService->sendOtp($validated, 'email_verification');
    }
    public function verifyOtpEmail(OtpSetRequest $request): JsonResponse
    {
        return $this->authService->verifyOtp($request->validated());
    }

    public function login(LoginRequest $request): JsonResponse
    {
        return $this->authService->login($request->validated());

    }

    public function sendResetOtp(Request $request): JsonResponse
    {
        $validated = $request->validate(['email' => 'required|email|exists:users,email']);

        return $this->authService->sendOtp($validated, 'password_reset');
    }

    public function verifyOtpReset(OtpSetRequest $request): JsonResponse
    {
        return $this->passwordService->verifyOtpForReset($request->validated());
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        return $this->passwordService->resetPassword($request->validated());
    }

    public function logout(): JsonResponse
    {
        return $this->authService->destroyTokens();
    }
}

