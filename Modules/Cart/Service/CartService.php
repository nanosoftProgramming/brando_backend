<?php

namespace Modules\Cart\Service;

use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Cart\App\Models\Cart;
use Modules\Cart\App\Models\CartItem;
use Modules\Cart\App\Models\CartItemAddon;
use Modules\Cart\App\Models\CartItemAttribute;
use Modules\Product\App\Models\Addon;
use Modules\Product\App\Models\Product;

class CartService
{
    /**
     * Get all cart items for authenticated client with summary
     */
    public function findAll($data = [])
    {
        $client_id = auth('client')->user()->id;
        $cart = Cart::where('client_id', $client_id)->first();

        if (!$cart) {
            return [
                'items' => [],
                'summary' => $this->getEmptyCartSummary(),
            ];
        }

        $cartItems = $cart->items()
            ->with([
                'product.restaurant',
                'usedProduct.client',
                'usedProduct.category',
                'addons.addon',
                'attributes.attributeValue.attribute',
                'attributes.attributeValue.value',
            ])
            ->get();

        $formattedItems = $this->formatCartItemsForResponse($cartItems);
        $summary = $this->calculateCartSummary($cart, $cartItems);

        return [
            'items' => $formattedItems,
            'summary' => $summary,
        ];
    }

    /**
     * Find cart by key-value pair
     */
    public function findBy($key, $value, $data = [], $relations = [])
    {
        $cart = Cart::with($relations)->where($key, $value)->first();

        return $cart;
    }

    /**
     * Add product to cart with validation and processing
     */
    public function create($data)
    {
        try {
            DB::beginTransaction();

            $client = auth('client')->user();
            $cart = $this->getOrCreateCart($client->id);

            $itemType = $data['item_type'] ?? 'product';

            if ($itemType === 'used_product') {
                $usedProduct = $this->getAndValidateUsedProduct($data['used_product_id']);
                $cartItem = $this->createUsedProductCartItem($cart, $usedProduct, $data);
            } else {
                $product = $this->getAndValidateProduct($data['product_id']);
                $cartItem = $this->createOrUpdateCartItem($cart, $product, $data);

                if (!empty($data['addons'])) {
                    $this->processCartItemAddons($cartItem, $data['addons']);
                }
            }

            DB::commit();

            return $cart->fresh([
                'items.product.restaurant',
                'items.usedProduct.client',
                'items.addons.addon',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update cart item
     */
    public function update($cartItem, $data)
    {
        try {
            DB::beginTransaction();

            if (isset($data['quantity'])) {
                $this->validateQuantity($data['quantity']);
                $cartItem->update(['quantity' => $data['quantity']]);
            }

            if (isset($data['notes'])) {
                $cartItem->update(['notes' => $data['notes']]);
            }

            if (isset($data['addons'])) {
                $this->syncCartItemAddons($cartItem, $data['addons']);
            }

            DB::commit();

            return $cartItem->fresh([
                'product.restaurant',
                'addons.addon',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete cart item
     */
    public function delete($cartItem)
    {
        return $this->deleteCartItem($cartItem);
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        $user = auth('client')->user();

        if ($user && $user->cart) {
            $user->cart->delete();
        }
    }

    /**
     * Get cart for current user
     */
    public function getCart()
    {
        $client_id = auth('client')->id();

        return Cart::where('client_id', $client_id)->first();
    }

    /**
     * Get specific cart item
     */
    public function getCartItem($cartItemId)
    {
        return CartItem::with([
            'product.restaurant',
            'addons.addon',
        ])->findOrFail($cartItemId);
    }

    /**
     * Increment cart item quantity
     */
    public function incrementCartItem($cartItem)
    {
        $cartItem->increment('quantity');

        return $cartItem->fresh();
    }

    /**
     * Decrement cart item quantity
     */
    public function decrementCartItem($cartItem)
    {
        if ($cartItem->quantity <= 1) {
            return $this->deleteCartItem($cartItem);
        }

        $cartItem->decrement('quantity');

        return $cartItem->fresh();
    }

    /**
     * Increment addon quantity
     */
    public function incrementAddon($cartItemAddon)
    {
        $cartItemAddon->increment('quantity');

        return $cartItemAddon->fresh();
    }

    /**
     * Decrement addon quantity
     */
    public function decrementAddon($cartItemAddon)
    {
        if ($cartItemAddon->quantity <= 1) {
            $cartItemAddon->delete();

            return true;
        }

        $cartItemAddon->decrement('quantity');

        return $cartItemAddon->fresh();
    }

    /**
     * Get cart summary only
     */
    public function getCartSummary()
    {
        $cart = $this->getCart();

        if (!$cart) {
            return $this->getEmptyCartSummary();
        }

        $cartItems = $cart->items()
            ->with([
                'product.restaurant',
                'addons.addon',
                'attributes.attributeValue.attribute',
                'attributes.attributeValue.value',
            ])
            ->get();

        return $this->calculateCartSummary($cart, $cartItems);
    }

    /**
     * Calculate cart summary - Updated with discount and delivery
     */
    private function calculateCartSummary($cart, $cartItems)
    {
        $totalItems = $cartItems->sum('quantity');
        $subtotalEgp = 0;
        $subtotalSar = 0;
        $discountedSubtotalEgp = 0;
        $discountedSubtotalSar = 0;

        $restaurants = [];

        foreach ($cartItems as $item) {
            // subtotal uses regular prices (item->subtotal_egp always regular)
            $subtotalEgp += $item->subtotal_egp + $item->addons_total_egp;
            $subtotalSar += $item->subtotal_sar + $item->addons_total_sar;

            // discounted_subtotal uses discounted prices
            $discountedSubtotalEgp += $item->discounted_subtotal_egp + $item->addons_total_egp;
            $discountedSubtotalSar += $item->discounted_subtotal_sar + $item->addons_total_sar;

            if ($item->product && $item->product->restaurant) {
                $restaurantId = $item->product->restaurant->id;

                if (!isset($restaurants[$restaurantId])) {
                    $restaurants[$restaurantId] = [
                        'id' => $restaurantId,
                        'name' => $item->product->restaurant->name,
                        'items_count' => 0,
                        'subtotal_egp' => 0,
                        'subtotal_sar' => 0,
                        'delivery_fee_egp' => 0,
                        'delivery_fee_sar' => 0,
                    ];
                }

                $restaurants[$restaurantId]['items_count'] += $item->quantity;
                $restaurants[$restaurantId]['subtotal_egp'] += $item->total_price_egp;
                $restaurants[$restaurantId]['subtotal_sar'] += $item->total_price_sar;
            }
        }

        $discountEgp = $this->calculateDiscount($subtotalEgp, 'egp');
        $discountSar = $this->calculateDiscount($subtotalSar, 'sar');
        $deliveryFeeEgp = $this->calculateDeliveryFee($restaurants, 'egp');
        $deliveryFeeSar = $this->calculateDeliveryFee($restaurants, 'sar');

        // ✅ FIX: Use smart total_price from items (not calculated subtotal)
        $smartTotalEgp = $cartItems->sum('total_price_egp');
        $smartTotalSar = $cartItems->sum('total_price_sar');

        return [
            'total_items' => $totalItems,
            'unique_products' => $cartItems->count(),

            'subtotal_egp' => round($subtotalEgp, 2),      // Smart total (best price)
            'subtotal_sar' => round($subtotalSar, 2),      // Smart total (best price)
            'discounted_subtotal_egp' => round($discountedSubtotalEgp, 2), // Always discounted
            'discounted_subtotal_sar' => round($discountedSubtotalSar, 2), // Always discounted
            'discount_egp' => round($discountEgp, 2),
            'discount_sar' => round($discountSar, 2),
            'delivery_fee_egp' => round($deliveryFeeEgp, 2),
            'delivery_fee_sar' => round($deliveryFeeSar, 2),
            'total_price_egp' => round($smartTotalEgp - $discountEgp + $deliveryFeeEgp, 2),  // ✅ Smart total
            'total_price_sar' => round($smartTotalSar - $discountSar + $deliveryFeeSar, 2),  // ✅ Smart total

            'restaurants' => array_values($restaurants),
            'restaurants_count' => count($restaurants),
            'is_empty' => $totalItems === 0,

            'has_discount' => $discountEgp > 0 || $discountSar > 0,
            'has_delivery_fee' => $deliveryFeeEgp > 0 || $deliveryFeeSar > 0,
        ];
    }

    /**
     * Calculate discount (placeholder for future implementation)
     */
    private function calculateDiscount($subtotal, $currency)
    {

        return 0;
    }

    /**
     * Calculate delivery fee (placeholder for future implementation)
     */
    private function calculateDeliveryFee($restaurants, $currency)
    {

        return 0;
    }

    /**
     * Get empty cart summary
     */
    private function getEmptyCartSummary()
    {
        return [
            'total_items' => 0,
            'unique_products' => 0,
            'subtotal_egp' => 0,
            'subtotal_sar' => 0,
            'discount_egp' => 0,
            'discount_sar' => 0,
            'delivery_fee_egp' => 0,
            'delivery_fee_sar' => 0,
            'total_price_egp' => 0,
            'total_price_sar' => 0,
            'restaurants' => [],
            'restaurants_count' => 0,
            'is_empty' => true,
            'has_discount' => false,
            'has_delivery_fee' => false,
        ];
    }

    /**
     * Validate and get product
     */
    private function getAndValidateProduct($productId)
    {
        $product = Product::with(['restaurant', 'addons', 'attributeValues.attribute', 'attributeValues.value'])->find($productId);

        if (!$product) {
            throw new Exception('Product not found');
        }

        if (!$product->is_active) {
            throw new Exception('Product is not available');
        }

        return $product;
    }

    /**
     * Get or create cart for client
     */
    private function getOrCreateCart($clientId)
    {
        return Cart::firstOrCreate(['client_id' => $clientId]);
    }

    /**
     * Create or update cart item
     */
    private function createOrUpdateCartItem($cart, $product, $data)
    {
        $this->validateQuantity($data['quantity']);
        $this->validateRequiredAttributes($product, $data['attributes'] ?? []);

        $existingItem = $this->findExistingCartItem($cart, $product->id, $data['attributes'] ?? []);

        if ($existingItem) {
            $existingItem->quantity += $data['quantity'];
            $existingItem->notes = $data['notes'] ?? $existingItem->notes;
            $existingItem->save();

            return $existingItem;
        }

        $cartItem = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => $data['quantity'],
            'notes' => $data['notes'] ?? null,
        ]);

        if (!empty($data['attributes'])) {
            foreach ($data['attributes'] as $attributeValueId) {

                $productAttributeValue = $product->attributeValues()
                    ->where('attribute_value_id', $attributeValueId)
                    ->first();

                if ($productAttributeValue) {
                    CartItemAttribute::create([
                        'cart_item_id' => $cartItem->id,
                        'product_attribute_value_id' => $productAttributeValue->id,
                    ]);
                }
            }
        }

        return $cartItem;
    }

    /**
     * Find existing cart item with same product and attributes
     */
    private function findExistingCartItem($cart, $productId, $attributes)
    {
        $cartItems = $cart->items()->where('product_id', $productId)->with('attributes.attributeValue')->get();

        foreach ($cartItems as $item) {
            $existingAttributeValueIds = $item->attributes
                ->pluck('attributeValue.attribute_value_id')
                ->sort()
                ->values()
                ->toArray();

            $newAttributeValueIds = collect($attributes)->sort()->values()->toArray();

            if ($existingAttributeValueIds === $newAttributeValueIds) {
                return $item;
            }
        }

        return null;
    }

    /**
     * Validate required attributes for product
     */
    private function validateRequiredAttributes($product, $attributes)
    {
        $product->load('attributeValues.attribute');
        $requiredAttributes = $product->attributeValues
            ->where('attribute.required', true)
            ->groupBy('attribute_id');

        if ($requiredAttributes->isEmpty()) {
            return;
        }
        if (empty($attributes)) {
            $requiredNames = $requiredAttributes->keys()
                ->map(fn($id) => $product->attributeValues->where('attribute_id', $id)->first()->attribute->name)
                ->implode(', ');
            throw new Exception("This product requires the following attributes: {$requiredNames}");
        }
        $validAttributeValueIds = $product->attributeValues->pluck('attribute_value_id')->toArray();
        foreach ($attributes as $attributeValueId) {
            if (!in_array($attributeValueId, $validAttributeValueIds)) {
                throw new Exception('Invalid attribute value selected for this product');
            }
        }
        foreach ($requiredAttributes as $attributeId => $attributeValues) {
            $hasRequiredAttribute = false;

            foreach ($attributes as $attributeValueId) {
                if (in_array($attributeValueId, $attributeValues->pluck('attribute_value_id')->toArray())) {
                    $hasRequiredAttribute = true;
                    break;
                }
            }
            if (!$hasRequiredAttribute) {
                $attributeName = $attributeValues->first()->attribute->name;
                throw new Exception("Required attribute '{$attributeName}' is missing");
            }
        }
    }

    private function processCartItemAddons($cartItem, $addons)
    {
        $this->validateAddons($cartItem->product, $addons);

        foreach ($addons as $addonData) {
            $quantity = $addonData['quantity'] ?? 1;
            $this->validateAddonQuantity($quantity);

            $existingAddon = CartItemAddon::where('cart_item_id', $cartItem->id)
                ->where('addon_id', $addonData['addon_id'])
                ->first();

            if ($existingAddon) {
                $existingAddon->increment('quantity', $quantity);
            } else {
                CartItemAddon::create([
                    'cart_item_id' => $cartItem->id,
                    'addon_id' => $addonData['addon_id'],
                    'quantity' => $quantity,
                ]);
            }
        }
    }

    /**
     * Sync cart item addons (for updates)
     */
    private function syncCartItemAddons($cartItem, $addons)
    {

        $cartItem->addons()->delete();

        if (!empty($addons)) {
            $this->processCartItemAddons($cartItem, $addons);
        }
    }

    /**
     * Validate addons for product
     */
    private function validateAddons($product, $addons)
    {
        $validAddonIds = $product->addons->pluck('id')->toArray();

        foreach ($addons as $addonData) {
            if (!isset($addonData['addon_id']) || !in_array($addonData['addon_id'], $validAddonIds)) {
                throw new Exception('Invalid addon selected for this product');
            }

            $addon = Addon::find($addonData['addon_id']);
            if (!$addon || !$addon->is_active) {
                throw new Exception('Selected addon is not available');
            }
        }
    }

    /**
     * Validate quantity
     */
    private function validateQuantity($quantity)
    {
        if (!is_numeric($quantity) || $quantity <= 0) {
            throw new Exception('Quantity must be a positive number');
        }
    }

    /**
     * Validate addon quantity
     */
    private function validateAddonQuantity($quantity)
    {
        if (!is_numeric($quantity) || $quantity <= 0) {
            throw new Exception('Addon quantity must be a positive number');
        }
    }

    /**
     * Delete cart item and all related data
     */
    private function deleteCartItem($cartItem)
    {
        $cartItem->addons()->delete();
        $cartItem->delete();

        return true;
    }

    /**
     * Format cart items for response grouped by restaurant
     */
    private function formatCartItemsForResponse($cartItems)
    {
        $restaurantsWithProducts = [];

        foreach ($cartItems as $cartItem) {
            $item = $cartItem->getItem();
            $itemType = $cartItem->item_type;

            if ($itemType === 'used_product') {
                $restaurantId = 'used_products';
                $restaurantName = 'Used Products';
            } else {
                $restaurantId = $item->restaurant->id;
                $restaurantName = $item->restaurant->name;
            }

            if (!isset($restaurantsWithProducts[$restaurantId])) {
                $restaurantsWithProducts[$restaurantId] = [
                    'restaurant_id' => $itemType === 'used_product' ? null : $restaurantId,
                    'restaurant_name' => $restaurantName,
                    'item_count' => 0,
                    'subtotal_egp' => 0,
                    'subtotal_sar' => 0,
                    'delivery_fee_egp' => 0,
                    'delivery_fee_sar' => 0,
                    'products' => [],
                ];
            }

            $restaurantsWithProducts[$restaurantId]['item_count'] += $cartItem->quantity;
            $restaurantsWithProducts[$restaurantId]['subtotal_egp'] += $cartItem->total_price_egp;
            $restaurantsWithProducts[$restaurantId]['subtotal_sar'] += $cartItem->total_price_sar;
            $restaurantsWithProducts[$restaurantId]['products'][] = [
                'id' => $cartItem->id,
                'item_type' => $itemType,
                'product_id' => $itemType === 'product' ? $item->id : null,
                'used_product_id' => $itemType === 'used_product' ? $item->id : null,
                'product_name' => $item->name,
                'product_description' => $item->description,
                'quantity' => $cartItem->quantity,
                'unit_price_egp' => $cartItem->unit_price_egp,
                'unit_price_sar' => $cartItem->unit_price_sar,
                'discounted_price_egp' => $cartItem->discounted_price_egp,
                'discounted_price_sar' => $cartItem->discounted_price_sar,
                'subtotal_egp' => $cartItem->subtotal_egp,
                'subtotal_sar' => $cartItem->subtotal_sar,
                'discounted_subtotal_egp' => $cartItem->discounted_subtotal_egp,
                'discounted_subtotal_sar' => $cartItem->discounted_subtotal_sar,
                'addons_total_egp' => $cartItem->addons_total_egp,
                'addons_total_sar' => $cartItem->addons_total_sar,
                'total_price_egp' => $cartItem->total_price_egp,
                'total_price_sar' => $cartItem->total_price_sar,
                'notes' => $cartItem->notes,
                'attributes' => $itemType === 'product' ? $cartItem->attributes->map(function ($attribute) {
                    return [
                        'id' => $attribute->id,
                        'product_attribute_value_id' => $attribute->product_attribute_value_id,
                        'attribute_id' => $attribute->attributeValue->attribute_id,
                        'attribute_name' => $attribute->attributeValue->attribute->name,
                        'value_id' => $attribute->attributeValue->attribute_value_id,
                        'value_name' => $attribute->attributeValue->value->attribute_value,
                        'price_egp' => $attribute->attributeValue->price_egp,
                        'price_sar' => $attribute->attributeValue->price_sar,
                    ];
                })->toArray() : [],
                'addons' => $itemType === 'product' ? $cartItem->addons->map(function ($addon) {
                    return [
                        'id' => $addon->id,
                        'addon_id' => $addon->addon->id,
                        'name' => $addon->addon->name,
                        'quantity' => $addon->quantity,
                        'unit_price_egp' => $addon->unit_price_egp,
                        'unit_price_sar' => $addon->unit_price_sar,
                        'total_price_egp' => $addon->total_price_egp,
                        'total_price_sar' => $addon->total_price_sar,
                    ];
                })->toArray() : [],
            ];
        }

        return array_values($restaurantsWithProducts);
    }

    private function getAndValidateUsedProduct($usedProductId)
    {
        $usedProduct = \Modules\Product\App\Models\UsedProduct::where('id', $usedProductId)
            ->where('is_sold', false)
            ->where('is_active', true)
            ->first();

        if (!$usedProduct) {
            throw new Exception('Used product not found or not available');
        }

        $existsInCart = CartItem::where('used_product_id', $usedProductId)
            ->where('item_type', 'used_product')
            ->exists();

        if ($existsInCart) {
            throw new Exception('This used product is already in a cart');
        }

        return $usedProduct;
    }

    private function createUsedProductCartItem($cart, $usedProduct, $data)
    {
        return CartItem::create([
            'cart_id' => $cart->id,
            'used_product_id' => $usedProduct->id,
            'item_type' => 'used_product',
            'quantity' => 1,
            'notes' => $data['notes'] ?? null,
        ]);
    }
}
