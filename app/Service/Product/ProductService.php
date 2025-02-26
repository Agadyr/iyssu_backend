<?php

namespace App\Service\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProductService
{
    /**
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Product::all();
    }

    /**
     *
     * @param array $data
     * @return Product
     * @throws HttpException
     */
    public function createProduct(array $data): Product
    {
        try {
            return Product::create($data);
        } catch (QueryException $e) {
            throw new HttpException(400, 'Ошибка при создании продукта');
        }
    }

    /**
     *
     * @param Product $product
     * @param array $data
     * @return Product
     * @throws HttpException
     */
    public function updateProduct(Product $product, array $data): Product
    {
        try {
            $product->update($data);
            return $product;
        } catch (\Exception $e) {
            throw new HttpException(400, 'Ошибка при обновлении продукта');
        }
    }

    /**
     *
     * @param Product $product
     * @return void
     * @throws HttpException
     */
    public function deleteProduct(Product $product): void
    {
        if (!$product->delete()) {
            throw new HttpException(400, 'Ошибка при удалении продукта');
        }
    }

    /**
     *
     * @param int $id
     * @return Product
     */
    public function getProductById(int $id): Product
    {
        return Product::findOrFail($id);
    }
}
