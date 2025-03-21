<?php

namespace App\Service\Favorite;

use App\Models\Favorite;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class FavoriteService
{
    private int $userId;

    public function __construct()
    {
        $this->userId = Auth::id();
    }

    /**
     * @param int $productId
     * @return Favorite
     */
    public function addToFavorite(int $productId): Favorite
    {
        return Favorite::updateOrCreate(
            ['user_id' => $this->userId, 'product_id' => $productId],
            ['updated_at' => now()]
        );
    }

    /**
     * @param int $productId
     * @return bool|null
     */
    public function removeFromFavorite(int $productId): ?bool
    {
        return Favorite::where('user_id', $this->userId)
            ->where('product_id', $productId)
            ->delete();
    }

    /**
     * @return Collection
     */
    public function getUserFavorites(): Collection
    {
        $cacheKey = "user_favorites_{$this->userId}";

        return cache()->remember($cacheKey, now()->addMinutes(1), function() {
            return Favorite::with(['product:id,name'])
            ->where('user_id', $this->userId)->get();
        });
    }
}
