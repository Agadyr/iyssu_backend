<?php

namespace App\Service\CartItem;

use App\Exceptions\ApiException;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use stdClass;

class CartItemService
{
    protected int $userId;

    public function __construct()
    {
        $this->userId = Auth::id();
    }

    /**
     * @param int $productId
     * @param int $volume_option
     * @param int $quantity Количество единиц (по умолчанию 1)
     * @param bool $replace Заменить существующее количество (по умолчанию false)
     * @return object
     * @throws ApiException
     */
    public function addProduct(int $productId, int $volume_option, int $quantity = 1, bool $replace = false)
    {
        $cartItem = CartItem::firstOrNew(
            ['user_id' => $this->userId, 'product_id' => $productId],
            ['quantity' => 0]
        );

        $product = $cartItem->product ?? Product::findOrFail($productId);

        $this->validateVolumeOption($product, $volume_option);

        $newQuantity = $replace ? $quantity : $cartItem->quantity + $quantity;

        $this->validateProductAvailability($product, $volume_option, $newQuantity);

        $cartItem->quantity = $newQuantity;
        $cartItem->volume_option = $volume_option;
        $cartItem->save();

        return $cartItem;
    }

    /**
     * @param Product $product
     * @param int $volume_option
     * @throws ApiException
     */
    private function validateVolumeOption(Product $product, int $volume_option): void
    {
        if (!in_array($volume_option, $product->volume_options, true)) {
            throw new ApiException(
                "Недопустимый объём. Доступные варианты: " . implode(', ', $product->volume_options),
                400
            );
        }
    }

    /**
     * @param Product $product
     * @param int $volume_option Выбранный объем
     * @param int $newQuantity Новое количество
     * @throws ApiException
     */
    private function validateProductAvailability(Product $product, int $volume_option, int $newQuantity): void
    {
        $requiredVolume = $newQuantity * $volume_option;
        if ($requiredVolume > $product->quantity) {
            $availableQuantity = intdiv($product->quantity, $volume_option);
            throw new ApiException(
                "Недостаточно товара в наличии. Доступно только {$availableQuantity} шт. по {$volume_option} мл.",
                400
            );
        }
    }

    /**
     * Удаление товара из корзины
     *
     * @param int $productId
     * @return bool
     */
    public function removeProduct(int $productId): bool
    {
        $result = CartItem::where('user_id', $this->userId)
            ->where('product_id', $productId)
            ->delete();

        return (bool)$result;
    }

    /**
     * @return stdClass
     */
    public function getAll(): stdClass
    {
        $items = CartItem::with(['product:id,name,price,unit'])
            ->where('user_id', $this->userId)
            ->get();

        $result = new stdClass();
        $result->items = $items;
        $result->totalPrice = $this->getTotalPrice($items);

        return $result;
    }

    /**
     * Очистка корзины
     *
     * @return bool
     */
    public function clear(): bool
    {
        $result = CartItem::where('user_id', $this->userId)->delete();
        return (bool)$result;
    }

    /**
     * Подсчет общей стоимости товаров в корзине
     *
     * @param Collection|null $items
     * @return float
     */
    public function getTotalPrice(?Collection $items = null): float
    {
        if ($items === null) {
            $items = CartItem::with(['product:id,price,unit'])
                ->where('user_id', $this->userId)
                ->get();
        }

        return $items->sum(function ($item) {
            $product = $item->product;
            return ($product->unit === 'ml' ? $product->price * $item->volume_option : $product->price) * $item->quantity;
        });
    }
}
