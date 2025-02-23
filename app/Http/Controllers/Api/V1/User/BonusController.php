<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\CountingBonusRequest;
use App\Models\User;
use App\Service\Bonus\BonusService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class BonusController extends Controller
{
    private BonusService $bonusService;

    public function __construct(BonusService $bonusService)
    {
        $this->bonusService = $bonusService;
    }

    public function getBonusPoints(): JsonResponse
    {
        /** @var User $user */

        $user = Auth::user();
        $bonusPoints = $this->bonusService->getBonusPoints($user);
        return response()->json(['bonus_points' => $bonusPoints]);
    }

    // Получить историю бонусов
    public function getBonusHistory(): JsonResponse
    {
        /** @var User $user */

        $user = Auth::user();
        $history = $this->bonusService->getBonusHistory($user);
        return response()->json(['history' => $history]);
    }

    // Начислить бонусы

    /**
     * @throws \Exception
     */
    public function addBonusPoints(CountingBonusRequest $request, User $user): JsonResponse
    {
        $amount = $request->validated()['amount'];
        $this->bonusService->addBonusPoints($user, $amount, 'начисление', 'Начисление за покупку');
        return response()->json(['message' => 'Bonus points added successfully']);
    }

    // Списать бонусы

    /**
     * @throws \Exception
     */
    public function deductBonusPoints(CountingBonusRequest $request, User $user): JsonResponse
    {
        $amount = $request->validated()['amount'];
        $this->bonusService->deductBonusPoints($user, $amount, 'списание', 'Списание за услугу');
        return response()->json(['message' => 'Bonus points deducted successfully']);
    }
}
