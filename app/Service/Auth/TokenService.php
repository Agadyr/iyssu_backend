<?php

namespace App\Service\Auth;

use App\Models\CustomToken;
use App\Models\User;
use Illuminate\Support\Str;

class TokenService
{
    public function createToken(User $user, string $type, int $expiresInMinutes = 60): string
    {
        $token = Str::random(64);

        CustomToken::create([
            'user_id' => $user->id,
            'token' => $token,
            'type' => $type,
            'expires_at' => now()->addMinutes($expiresInMinutes),
        ]);

        return $token;
    }

    public function verifyToken(User $user, string $token, string $type): bool
    {
        $token = CustomToken::where('user_id', $user->id)
            ->where('token', $token)
            ->where('type', $type)
            ->where('expires_at', '>', now())
            ->first();

        if ($token) {
            $token->delete();
            return true;
        }

        return false;
    }
}
