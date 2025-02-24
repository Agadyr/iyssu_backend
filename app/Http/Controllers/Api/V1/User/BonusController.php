<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\CountingBonusRequest;
use App\Models\User;
use App\Service\Bonus\BonusService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class BonusController extends Controller
{
    private BonusService $bonusService;
    private User $user;

    public function __construct(BonusService $bonusService)
    {
        $this->bonusService = $bonusService;
        $user = Auth::user();
        if (!$user instanceof User) {
            throw new \TypeError('User is not authenticated or is not of type User.');
        }

        $this->user = $user;
    }

    public function getBonusPoints(): JsonResponse
    {
        $bonusPoints = $this->bonusService->getBonusPoints($this->user);
        return response()->json(['bonus_points' => $bonusPoints]);
    }

    // Получить историю бонусов
    public function getBonusHistory(): JsonResponse
    {
        $history = $this->bonusService->getBonusHistory($this->user);
        return response()->json(['history' => $history]);
    }

    // Начислить бонусы

    /**
     * @throws Exception
     */
    public function addBonusPoints(CountingBonusRequest $request, User $user): JsonResponse
    {
        $amount = $request->validated()['amount'];
        $this->bonusService->addBonusPoints($user, $amount, 'начисление', 'Начисление за покупку');
        return response()->json(['message' => 'Bonus points added successfully']);
    }

    // Списать бонусы

    /**
     * @throws Exception
     */
    public function deductBonusPoints(CountingBonusRequest $request, User $user): JsonResponse
    {
        $amount = $request->validated()['amount'];
        $this->bonusService->deductBonusPoints($user, $amount, 'списание', 'Списание за услугу');
        return response()->json(['message' => 'Bonus points deducted successfully']);
    }
}
