<?php

namespace App\Http\Controllers\Api\V1\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\SearchRequest;
use App\Service\Product\ProductSearchService;
use Illuminate\Http\JsonResponse;

class ProductSearchController extends Controller
{
    protected ProductSearchService $searchService;

    /**
     * Конструктор контроллера
     *
     * @param ProductSearchService $searchService
     */
    public function __construct(ProductSearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    /**
     * Поиск продуктов по заданным критериям
     *
     * @param SearchRequest $request
     * @return JsonResponse
     */

    public function search(SearchRequest $request): JsonResponse
    {
        $products = $this->searchService->searchProducts($request->validated());
        return response()->json($products);
    }
}
