<?php

namespace App\Http\Controllers\Api\V1\Product;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use App\Service\Product\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
class ProductController extends Controller
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json($this->productService->getAll());
    }

    /**
     * @return JsonResponse
     */

    public function popular(): JsonResponse
    {
        return response()->json($this->productService->getPopulars());
    }

    /**
     * @return JsonResponse
     */

    public function today(): JsonResponse
    {
        return response()->json($this->productService->getToday());
    }

    /**
     *
     * @param StoreProductRequest $request
     * @return JsonResponse
     * @throws ApiException
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        return response()->json($this->productService->createProduct($request->validated()), Response::HTTP_CREATED);
    }

    /**
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        return response()->json($this->productService->getProductById($id));
    }

    /**
     *
     * @param UpdateProductRequest $request
     * @param Product $product
     * @return JsonResponse
     * @throws ApiException
     */
    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        return response()->json($this->productService->updateProduct($product, $request->validated()));
    }

    /**
     *
     * @param Product $product
     * @return JsonResponse
     * @throws ApiException
     */
    public function destroy(Product $product): JsonResponse
    {
        $this->productService->deleteProduct($product);
        return response()->json(['message' => 'Product deleted']);
    }

    /**
     *
     * @param Request $request
     * @param Product $product
     * @return JsonResponse
     * @throws ApiException
     */

    public function uploadImages(Request $request, Product $product): JsonResponse
    {
        $data = $request->validate([
            'images'   => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $this->productService->storeProductImages($product, ['images' => $request->file('images')]);
        return response()->json(['message' => 'Images uploaded to product']);
    }


}
