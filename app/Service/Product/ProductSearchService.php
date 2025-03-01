<?php

namespace App\Service\Product;

use App\DTO\ProductSearchDTO;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductSearchService
{
    public function searchProducts(array $filters): LengthAwarePaginator
    {
        $searchDTO = ProductSearchDTO::fromValidated($filters);
        return $this->search($searchDTO);
    }

    private function search(ProductSearchDTO $searchDTO): LengthAwarePaginator
    {
        $query = Product::query();
        $this->applyTextSearch($query, $searchDTO->query);
        $this->applyScentFilter($query, $searchDTO->scent);
        $this->applyPriceFilter($query, $searchDTO->priceMin, $searchDTO->priceMax);
        $this->applyBasicFilters($query, $searchDTO);
        $this->applySorting($query, $searchDTO->sort);

        return $query->paginate($searchDTO->perPage ?? 12);
    }

    /**
     * Применяет фильтр полнотекстового поиска
     *
     * @param Builder $query
     * @param string|null $searchText
     * @return void
     */

    private function applyTextSearch(Builder $query, ?string $searchText): void
    {
        if (!$searchText) {
            return;
        }

        $query->where(function ($q) use ($searchText) {
            $q->whereRaw(
                "search_vector @@ websearch_to_tsquery('russian', ?)",
                [$searchText]
            )
                ->orWhere('name', 'ILIKE', "%{$searchText}%")
                ->orWhere('description', 'ILIKE', "%{$searchText}%");
        });
    }

    /**
     * Применяет фильтр по аромату (поиск в JSON массиве)
     *
     * @param Builder $query
     * @param string|null $scent
     * @return void
     */

    private function applyScentFilter(Builder $query, ?string $scent): void
    {
        if (!$scent) {
            return;
        }

        $query->whereRaw(
            "EXISTS (
                SELECT 1 FROM jsonb_array_elements_text(scent) AS scent_value
                WHERE scent_value ILIKE ?
            )",
            ["%{$scent}%"]
        );
    }

    /**
     * Применяет базовые фильтры (бренд, категория, рейтинг, скидки, новинки)
     *
     * @param Builder $query
     * @param ProductSearchDTO $searchDTO
     * @return void
     */

    private function applyBasicFilters(Builder $query, ProductSearchDTO $searchDTO): void
    {
        if ($searchDTO->brand) {
            $query->where('brand', $searchDTO->brand);
        }

        if ($searchDTO->categoryId) {
            $query->where('category_id', $searchDTO->categoryId);
        }

        if ($searchDTO->rating) {
            $query->where('rating', '>=', $searchDTO->rating);
        }

        if ($searchDTO->discount) {
            $query->where('discount', '>', 0);
        }

        if ($searchDTO->isNew) {
            $query->where('is_new', true);
        }

        if ($searchDTO->gender) {
            $query->where('gender', $searchDTO->gender);
        }
    }

    /**
     * Применяет фильтры по минимальной и максимальной цене
     *
     * @param Builder $query
     * @param float|null $minPrice
     * @param float|null $maxPrice
     * @return void
     */

    private function applyPriceFilter(Builder $query, ?float $minPrice, ?float $maxPrice): void
    {
        if ($minPrice) {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }
    }

    /**
     * Применяет сортировку результатов
     *
     * @param Builder $query
     * @param string|null $sort
     * @return void
     */

    private function applySorting(Builder $query, ?string $sort): void
    {
        $sortField = match ($sort) {
            'price_asc' => 'price',
            'price_desc' => 'price desc',
            'rating_desc' => 'rating desc',
            default => 'id desc'
        };

        [$field, $direction] = str_contains($sortField, ' ')
            ? explode(' ', $sortField) : [$sortField, 'asc'];

        $query->orderBy($field, $direction);
    }
}
