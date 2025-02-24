<?php

namespace App\Service\Profile;

use App\Models\User;
use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserProfileService
{
    public function getUserData(User $user): array
    {
        $user->load(['bonusPoints', 'bonusHistories']);
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'email_verified_at' => $user->email_verified_at?->toDateTimeString(),
            'phone' => $user->phone,
            'city' => $user->city,
            'bonus_points' => $user->bonusPoints?->amount ?? 0,
            'bonus_histories' => $user->bonusHistories,
            'created_at' => $user->created_at->toDateTimeString(),
        ];
    }

    /**
     * @throws Exception
     */
    public function updateUser(User $user, array $data): User
    {
        try {
            $user->update($data);
        } catch (Exception) {
            throw new HttpException('Something went wrong');
        }
        return $user;
    }
}
