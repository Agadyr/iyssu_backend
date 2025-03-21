<?php

namespace App\DTO;

class ProductSearchDTO
{
    public function __construct(
        public ?string $query = null,
        public ?string $scent = null,
        public ?float $priceMin = null,
        public ?float $priceMax = null,
        public ?string $brand = null,
        public ?string $gender = null,
        public ?int $categoryId = null,
        public ?float $rating = null,
        public ?float $discount = null,
        public ?bool $isNew = null,
        public ?string $sort = null,
        public ?int $perPage = null,
    )
    {}

    /**
     * Создать DTO из валидированных данных запроса
     *
     * @param array $validatedData
     * @return self
     */

    public static function fromValidated(array $validatedData): ProductSearchDTO
    {
        return new self(
            query: $validatedData['query'] ?? null,
            scent: $validatedData['scent'] ?? null,
            priceMin: $validatedData['price_min'] ?? null,
            priceMax: $validatedData['price_max'] ?? null,
            brand: $validatedData['brand'] ?? null,
            gender: $validatedData['gender'] ?? null,
            categoryId: $validatedData['category_id'] ?? null,
            rating: $validatedData['rating'] ?? null,
            discount: isset($validatedData['discount']) ? (bool)$validatedData['discount'] : null,
            isNew: isset($validatedData['is_new']) ? (bool)$validatedData['is_new'] : null,
            sort: $validatedData['sort'] ?? null,
            perPage: $validatedData['per_page'] ?? 12
        );
    }
}
