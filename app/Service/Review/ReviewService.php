<?php

namespace App\Service\Review;

use App\Exceptions\ApiException;
use App\Jobs\Review\UpdateProductRatingJob;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewService
{
    public function createReview(array $data): Review
    {
        $review = Review::create([
            'user_id' => Auth::id(),
            'product_id' => $data['product_id'],
            'comment' => $data['comment'],
            'rating' => $data['rating'],
        ]);

        dispatch(new UpdateProductRatingJob($data['product_id']));

        return $review;
    }

    public function getReviewsByProduct(int $productId)
    {
        return Review::where('product_id', $productId)->latest()->get();
    }

    /**
     * @throws ApiException
     */
    public function deleteReview(Review $review): void
    {
        try {
            DB::transaction(static function () use ($review) {
                if ($review->delete()) {
                    throw new ApiException('Не удалось удалить отзыв', 400);
                }
            });
        } catch (\Exception) {
            throw new ApiException('Ошибка при удалении отзыва', 500);
        }
    }


}
