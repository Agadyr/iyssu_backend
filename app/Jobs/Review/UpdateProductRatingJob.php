<?php

namespace App\Jobs\Review;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class UpdateProductRatingJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    protected int $productId;
    public function __construct(int $productId)
    {
        $this->productId = $productId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $averageRating = Review::where('product_id', $this->productId)->avg('rating');

        Product::where('id', $this->productId)->update([
            'rating' => round($averageRating, 2),
        ]);
    }
}
