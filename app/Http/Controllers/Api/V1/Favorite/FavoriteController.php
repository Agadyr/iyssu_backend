<?php

namespace App\Http\Controllers\Api\V1\Favorite;

use App\Http\Controllers\Controller;
use App\Http\Requests\Favorite\FavoriteRequest;
use App\Service\Favorite\FavoriteService;
use Illuminate\Http\JsonResponse;

class FavoriteController extends Controller
{
    private FavoriteService $favoriteService;

    public function __construct(FavoriteService $favoriteService)
    {
        $this->favoriteService = $favoriteService;
    }

    /**
     * @param FavoriteRequest $request
     * @return JsonResponse
     */

    public function add(FavoriteRequest $request): JsonResponse
    {
        $favorite = $this->favoriteService->addToFavorite($request->validated('product_id'));

        return response()->json($favorite, 201);
    }

    /**
     * @param FavoriteRequest $request
     * @return JsonResponse
     */
    public function remove(FavoriteRequest $request): JsonResponse
    {
        $this->favoriteService->removeFromFavorite($request->validated('product_id'));

        return response()->json(['message' => 'Товар удален из избранного'], 200);
    }

    /**
     * @return JsonResponse
     */

    public function index(): JsonResponse
    {
        $favorites = $this->favoriteService->getUserFavorites();

        return response()->json($favorites);
    }

}
