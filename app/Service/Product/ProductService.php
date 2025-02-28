<?php

namespace App\Service\Product;

use App\Exceptions\ApiException;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class ProductService
{
    /**
     * Получить все продукты.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Product::with( 'category')->get();
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
}
