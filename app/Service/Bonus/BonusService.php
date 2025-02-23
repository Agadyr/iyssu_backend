<?php

namespace App\Service\Bonus;

use App\Models\BonusHistory;
use App\Models\User;
use Exception;

class BonusService
{
    public function getBonusPoints(User $user): int
    {
        return $user->bonusPoints->amount ?? 0;
    }

    public function getBonusHistory(User $user): \Illuminate\Database\Eloquent\Collection
    {
        return $user->bonusHistories()->orderBy('created_at', 'desc')->get();
    }

    /**
     * @throws Exception
     */
    public function addBonusPoints(User $user, int $amount, string $operationType, string $description = null): void
    {
        $this->updateBonusPoints($user, abs($amount), $operationType, $description);
    }

    /**
     * @throws Exception
     */
    public function deductBonusPoints(User $user, int $amount, string $operationType, string $description = null): void
    {
        $this->updateBonusPoints($user, -abs($amount), $operationType, $description);
    }

    /**
     * @throws Exception
     */
    protected function updateBonusPoints(User $user, int $amount, string $operationType, string $description = null): void
    {
        try {
            $bonusPoints = $user->bonusPoints()->firstOrCreate(['user_id' => $user->id], ['amount' => 0]);
            $bonusPoints->amount += $amount;
            $bonusPoints->save();

            BonusHistory::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'operation_type' => $operationType,
                'description' => $description,
            ]);

        } catch (Exception) {
            throw new Exception("Failed to update bonus points: ");
        }
    }
}
