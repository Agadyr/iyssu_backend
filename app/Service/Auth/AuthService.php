<?php

namespace App\Service\Auth;

use App\Exceptions\ApiException;
use App\Mail\SendOtpCode;
use App\Models\OtpCode;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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

        auth()->login($user);

        $token = Str::random(40);

        Cookie::queue(Cookie::make('auth_token', $token, 60 * 24 * 7, sameSite: 'none'));

        return [
            'user' => $user,
        ];
    }

    /**
     * @throws ValidationException
     */
    public function login(array $data): array
    {
        if (!auth()->attempt(['email' => $data['email'], 'password' => $data['password']])) {
            throw ValidationException::withMessages([
                'email' => ['Введенные данные не валидны'],
            ]);
        }

        $user = auth()->user();

        $token = Str::random(40);
        Cookie::queue('auth_token', $token, 60 * 24 * 7); // 7 дней

        return ['user' => $user];
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
     * @throws ApiException
     */
    public function verifyOtp(array $data)
    {
        /** @var User $user */
        $user = auth()->user();

        if ($user->email_verified_at) {
            throw new ApiException(409, 'User already verified.');
        }

        $otp = OtpCode::where('user_id', $user->id)
            ->where('code', $data['code'])
            ->where('type', 'email_verification')
            ->where('expires_at', '>', now())
            ->first();

        if (!$otp) {
            throw new ApiException(400, 'Invalid or expired OTP.');
        }

        $user->email_verified_at = now();
        $user->save();

        $otp->delete();

        return $user;
    }

    /**
     * @throws ApiException|Exception
     */
    protected function generateOtp(): array
    {
        return [
            random_int(1000, 9999),
            now()->addSeconds(180)
        ];
    }
}
