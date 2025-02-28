<?php

namespace App\Service\Category;

use App\Exceptions\ApiException;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;

class CategoryService
{
    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Category::all();
    }

    /**
     * @param array $data
     * @return Category
     * @throws ApiException
     */
    public function store(array $data): Category
    {
        try {
            return Category::create($data);
        } catch (QueryException $e) {
            throw new ApiException(400, 'Ошибка при создании категории');
        }
    }

    /**
     * @param Category $category
     * @param array $data
     * @return Category
     * @throws ApiException
     */

    public function update(Category $category, array $data): Category
    {
        try {
            $category->update($data);
            return $category;
        } catch (\Exception $e) {
            throw new ApiException(400, 'Ошибка при обновлении категории');
        }
    }

    /**
     * @param Category $category
     * @return void
     * @throws ApiException
     */
    public function destroy(Category $category): void
    {
        if (!$category->delete()) {
            throw new ApiException(400, 'Ошибка при удалении категории');
        }
    }
}
