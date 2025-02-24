<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\ProfileUpdateRequest;
use App\Models\User;
use App\Service\Profile\UserProfileService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    private UserProfileService $service;
    private User $user;

    public function __construct(UserProfileService $service)
    {
        $this->service = $service;
        $user = Auth::user();
        if (!$user instanceof User) {
            throw new \TypeError('User is not authenticated or is not of type User.');
        }

        $this->user = $user;
    }

    public function me(): JsonResponse
    {
        $userData = $this->service->getUserData($this->user);
        return response()->json($userData);
    }

    public function update(ProfileUpdateRequest $request): JsonResponse
    {
        try {
            $user = $this->service->updateUser($this->user, $request->validated());
            return response()->json([
                'message' => 'Profile updated successfully',
                'user' => $this->service->getUserData($user),
            ]);
        } catch (Exception) {
            return response()->json([
                'message' => 'Failed to update profile',
            ], 500);
        }
    }
}
