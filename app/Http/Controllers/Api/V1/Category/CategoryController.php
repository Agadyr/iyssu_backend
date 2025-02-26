<?php

namespace App\Http\Controllers\Api\V1\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Category;
use App\Service\Category\CategoryService;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(): JsonResponse
    {
        return response()->json($this->categoryService->getAll());
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        return response()->json($this->categoryService->store($request->validated()));
    }

    public function show(Category $category): JsonResponse
    {
        return response()->json($category);
    }

    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        return response()->json($this->categoryService->update($category, $request->validated()));
    }

    public function destroy(Category $category): JsonResponse
    {
        $this->categoryService->destroy($category);
        return response()->json(['Deleted']);
    }

}
