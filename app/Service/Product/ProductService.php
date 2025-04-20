<?php

namespace App\Service\Product;

use App\Exceptions\ApiException;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Mockery\Exception;

class ProductService
{
    /**
     * Получить все продукты.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        $cacheKey = "products";

        return cache()->remember($cacheKey, now()->addMinutes(10), function () {
            return Product::with(['category'])->get();
        });
    }

    /**
     * Получить популярные продукты.
     *
     * @return Collection
     */

    public function getPopulars(): Collection
    {
        $cacheKey = "popular_products";

        return cache()->remember($cacheKey, now()->addMinutes(10), function () {
            return Product::with(['category'])
                ->withCount('reviews')
                ->orderByDesc('reviews_count')
                ->get();
        });
    }

    public function getToday(): Collection
    {
        $cacheKey = "today_products";

        return cache()->remember($cacheKey, now()->addHours(12), function () {
            return Product::with(['category'])
                ->withCount('reviews')
                ->orderByDesc('discount')
                ->take(6)
                ->get();
        });
    }
    /**
     * Создать новый продукт.
     *
     * @param array $data
     * @return Product
     * @throws ApiException
     */
    public function createProduct(array $data): Product
    {
        try {
            if (!empty($data['images'])) {
                $imageUrls = [];

                foreach ($data['images'] as $image) {
                    $path = $image->store('products', 'public');
                    $imageUrls[] = Storage::url($path);
                }
                $data['image_url'] = $imageUrls;
            }

            return Product::create($data);
        } catch (QueryException) {
            throw new ApiException('Ошибка при создании продукта', 400);
        }
    }

    /**
     * Обновить данные продукта.
     *
     * @param Product $product
     * @param array $data
     * @return Product
     * @throws ApiException
     */
    public function updateProduct(Product $product, array $data): Product
    {
        try {
            $product->update($data);
            return $product;
        } catch (\Exception) {
            throw new ApiException('Ошибка при обновлении продукта', 400);
        }
    }

    /**
     * Удалить продукт.
     *
     * @param Product $product
     * @return void
     * @throws ApiException
     */
    public function deleteProduct(Product $product): void
    {
        try {
            DB::transaction(static function () use ($product) {
                if ($product->delete()) {
                    throw new ApiException('Не удалось удалить продукт', 400);
                }
            });
        } catch (\Exception) {
            throw new ApiException('Ошибка при удалении продукта', 500);
        }
    }

    /**
     * Получить продукт по ID.
     *
     * @param int $id
     * @return Product
     */
    public function getProductById(int $id): Product
    {
        return Product::with([
            'reviews' => function ($query) {
                $query->select('id', 'user_id', 'product_id', 'comment', 'rating', 'created_at')
                    ->with(['user:id,name']);
            }
        ])->findOrFail($id);
    }

    /**
     * @throws ApiException
     */
    public function storeProductImages(Product $product, array $data): void
    {
        try {
            $existingImages = $product->image_url ?? [];

            foreach ($data['images'] as $image) {
                $fileHash = md5_file($image->getPathname());
                $fileName = $image->getClientOriginalName();
                $storagePath = "products/$fileHash-$fileName";

                if (!in_array(Storage::url($storagePath), $existingImages, true)) {
                    $path = $image->storeAs('products', "$fileHash-$fileName", 'public');
                    $imageUrls[] = Storage::url($path);
                }
            }

            if (!empty($imageUrls)) {
                $product->update([
                    'image_url' => array_merge($existingImages, $imageUrls)
                ]);
            }

        } catch (\Exception) {
            throw new ApiException('Загрузка изображений не удалась');
        }
    }

}
