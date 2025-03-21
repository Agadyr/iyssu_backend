<?php

namespace App\Http\Controllers\Api\V1\CartItem;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CartItem\CartItemRequest;
use App\Service\CartItem\CartItemService;
use Illuminate\Http\JsonResponse;

class CartItemController extends Controller
{
    protected CartItemService $cartItemService;

    public function __construct(CartItemService $cartItemService)
    {
        $this->cartItemService = $cartItemService;
    }

    /**
     * Добавление товара в корзину
     *
     * @param CartItemRequest $request
     * @return JsonResponse
     * @throws ApiException
     */

    public function addProduct(CartItemRequest $request): JsonResponse
    {
        $result = $this->cartItemService->addProduct(
            $request->validated('product_id'),
            $request->validated('volume_option'),
            $request->validated('quantity'),
        );

        return response()->json($result);
    }

    /**
     * Удаление товара из корзины
     *
     * @param int $productId
     * @return JsonResponse
     */
    public function removeProduct(int $productId): JsonResponse
    {
        $result = $this->cartItemService->removeProduct($productId);
        return response()->json(['message' => $result]);
    }

    /**
     * Получение всех товаров в корзине
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $result = $this->cartItemService->getAll();
        return response()->json($result);
    }

    /**
     * Очистка корзины
     *
     * @return JsonResponse
     */
    public function clear(): JsonResponse
    {
        $result = $this->cartItemService->clear();
        return response()->json(['message' => $result]);
    }
}
