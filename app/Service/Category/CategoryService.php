<?php

namespace App\Service\Category;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
     * @throws HttpException
     */
    public function store(array $data): Category
    {
        try {
            return Category::create($data);
        } catch (QueryException $e) {
            throw new HttpException(400, 'Ошибка при создании категории');
        }
    }

    /**
     * @param Category $category
     * @param array $data
     * @return Category
     * @throws HttpException
     */

    public function update(Category $category, array $data): Category
    {
        try {
            $category->update($data);
            return $category;
        } catch (\Exception $e) {
            throw new HttpException(400, 'Ошибка при обновлении категории');
        }
    }

    /**
     * @param Category $category
     * @return void
     * @throws HttpException
     */
    public function destroy(Category $category): void
    {
        if (!$category->delete()) {
            throw new HttpException(400, 'Ошибка при удалении категории');
        }
    }
}
