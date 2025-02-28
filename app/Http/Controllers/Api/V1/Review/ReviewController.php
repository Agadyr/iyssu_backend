<?php

namespace App\Http\Controllers\Api\V1\Review;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Review\ReviewRequest;
use App\Models\Review;
use App\Service\Review\ReviewService;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    protected ReviewService $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    /**
     * @param ReviewRequest $request
     * @return JsonResponse
     */
    public function store(ReviewRequest $request): JsonResponse
    {
        $review = $this->reviewService->createReview($request->validated());
        return response()->json($review, 201);
    }

    /**
     * @param int $productId
     * @return JsonResponse
     */

    public function index(int $productId): JsonResponse
    {
        $reviews = $this->reviewService->getReviewsByProduct($productId);
        return response()->json($reviews);
    }

    /**
     * @throws ApiException
     */
    public function destroy(Review $review): JsonResponse
    {
        $this->reviewService->deleteReview($review);
        return response()->json(['Review deleted']);
    }
}
