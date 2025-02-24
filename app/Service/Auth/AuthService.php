<?php

namespace App\Service\Auth;

use App\Mail\SendOtpCode;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthService
{
    public function register(array $data): array
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'email_verified_at' => null,
        ]);

        $token = $user->createToken('auth_token', ['*'], now()->addDay(1))->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * @throws ValidationException
     */
    public function login(array $data): array
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth_token', ['*'], now()->addHours(5))->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function sendOtp(array $data, string $type): void
    {
        $user = User::where('email', $data['email'])->firstOrFail();

        [$otp, $expiresAt] = $this->generateOtp();

        $otp = OtpCode::updateOrCreate(
            ['user_id' => $user->id, 'type' => $type],
            ['code' => $otp, 'expires_at' => $expiresAt]
        );

        Mail::to($data['email'])->queue(new SendOtpCode($user, $otp));
    }

    /**
     * @throws HttpException
     */
    public function verifyOtp(array $data)
    {
        /** @var User $user */
        $user = auth()->user();

        if ($user->email_verified_at) {
            throw new HttpException(409, 'User already verified.');
        }

        $otp = OtpCode::where('user_id', $user->id)
            ->where('code', $data['code'])
            ->where('type', 'email_verification')
            ->where('expires_at', '>', now())
            ->first();

        if (!$otp) {
            throw new HttpException(400, 'Invalid or expired OTP.');
        }

        $user->email_verified_at = now();
        $user->save();

        $otp->delete();

        return $user;
    }

    public function destroyTokens(): void
    {
        auth()->user()->tokens()->delete();
    }

    /**
     * @throws \HttpException
     * @throws Exception
     */
    protected function generateOtp(): array
    {
        return [
            random_int(1000, 9999),
            now()->addSeconds(180)
        ];
    }
}
