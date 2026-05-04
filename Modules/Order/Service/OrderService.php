<?php

namespace Modules\Order\Service;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Cart\Service\CartService;
use Modules\Coupon\App\Models\Coupon;
use Modules\Order\App\Models\Order;
use Modules\Order\App\Models\OrderDetail;
use Modules\Order\App\Models\OrderDetailAddon;
use Modules\Order\App\Models\OrderDetailAttribute;
use Modules\Order\App\Models\OrderStatus;

final class OrderService
{
    public function __construct(
        protected CartService $cartService
    ) {
    }

    public function getAllOrders(int $clientId): Collection
    {
        return Order::with($this->orderRelations())
            ->where('client_id', $clientId)
            ->latest()
            ->get();
    }

    public function findAll(array $filters = []): Collection
    {
        return Order::with(array_merge($this->orderRelations(), ['client']))
            ->filter($filters)
            ->latest()
            ->get();
    }

    public function createFromCart(array $data): Collection
    {
        return DB::transaction(function () use ($data) {
            $cartData = $this->cartService->findAll();
            $clientId = auth('client')->id();

            if (empty($cartData['items']) || count($cartData['items']) === 0) {
                throw new Exception('Cart is empty. Please add items to cart before creating an order.');
            }

            $orders = collect();

            foreach ($cartData['items'] as $restaurantData) {
                $order = $this->createOrder(
                    $this->buildOrderData($data, $restaurantData, $clientId)
                );

                $this->createOrderDetails($order, $restaurantData['products']);

                $orders->push($order->load($this->orderRelations()));
            }

            $this->cartService->clear();

            return $orders;
        });
    }

    private function buildOrderData(array $data, array $restaurantData, int $clientId): array
    {
        $currency = $data['currency'] ?? 'egp';
        $subtotal = $restaurantData["subtotal_{$currency}"];
        [$discount, $discountType] = $this->calculateDiscount($data['coupon_id'] ?? null, $subtotal);
        $deliveryFee = 0;

        return [
            'order_no' => $this->generateOrderNumber(),
            'client_id' => $clientId,
            'address_id' => $data['address_id'],
            'restaurant_id' => $restaurantData['restaurant_id'],
            'coupon_id' => $data['coupon_id'] ?? null,
            'order_status_id' => OrderStatus::first()->id,
            'payment_method' => $data['payment_method'],
            'currency' => $currency,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'discount_type' => $discountType,
            'tax' => 0,
            'delivery_fee' => $deliveryFee,
            'total' => $subtotal - $discount + $deliveryFee,
            'quantity' => $restaurantData['item_count'],
            'note' => $data['note'] ?? null,
        ];
    }

    private function createOrder(array $data): Order
    {
        return Order::create($data);
    }

    private function createOrderDetails(Order $order, array $products): void
    {
        $currency = $order->currency;
        foreach ($products as $product) {
            $itemType = $product['item_type'] ?? 'product';

            if ($itemType === 'used_product') {
                $usedProduct = \Modules\Product\App\Models\UsedProduct::where('id', $product['used_product_id'])
                    ->where('is_sold', false)
                    ->lockForUpdate()
                    ->first();

                if (!$usedProduct) {
                    throw new \Exception('Used product is no longer available');
                }

                $unitPrice = $product["unit_price_{$currency}"];

                $orderDetail = OrderDetail::create([
                    'order_id' => $order->id,
                    'used_product_id' => $product['used_product_id'],
                    'item_type' => 'used_product',
                    'quantity' => 1,
                    'price' => $unitPrice,
                    'total' => $unitPrice,
                    'note' => $product['notes'] ?? null,
                ]);

                $usedProduct->markAsSold();

            } else {
                $unitPrice = $product["discounted_price_{$currency}"] ?: $product["unit_price_{$currency}"];
                $attributePrice = 0;
                if (!empty($product['attributes'])) {
                    foreach ($product['attributes'] as $attr) {
                        $attributePrice += $attr["price_{$currency}"] ?? 0;
                    }
                }
                $productTotal = ($unitPrice + $attributePrice) * $product['quantity'];

                $addonsTotal = 0;
                if (!empty($product['addons'])) {
                    foreach ($product['addons'] as $addon) {
                        $addonsTotal += $addon["total_price_{$currency}"] ?? 0;
                    }
                }
                $orderDetail = OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $product['product_id'],
                    'item_type' => 'product',
                    'quantity' => $product['quantity'],
                    'price' => $unitPrice + $attributePrice,
                    'total' => $productTotal + $addonsTotal,
                    'note' => $product['notes'] ?? null,
                ]);

                if (!empty($product['attributes'])) {
                    foreach ($product['attributes'] as $attr) {
                        OrderDetailAttribute::create([
                            'order_detail_id' => $orderDetail->id,
                            'product_attribute_value_id' => $attr['product_attribute_value_id'],
                            'price' => $attr["price_{$currency}"] ?? 0,
                        ]);
                    }
                }

                if (!empty($product['addons'])) {
                    foreach ($product['addons'] as $addon) {
                        OrderDetailAddon::create([
                            'order_detail_id' => $orderDetail->id,
                            'addon_id' => $addon['addon_id'],
                            'quantity' => $addon['quantity'],
                            'unit_price' => $addon["unit_price_{$currency}"] ?? 0,
                            'total_price' => $addon["total_price_{$currency}"] ?? 0,
                        ]);
                    }
                }
            }
        }
    }

    private function calculateDiscount(?int $couponId, float $subtotal): array
    {
        if (!$couponId) {
            return [0, null];
        }

        $coupon = Coupon::find($couponId);

        return $coupon
            ? [$coupon->discount($subtotal), $coupon->type]
            : [0, null];
    }

    private function orderRelations(): array
    {
        return [
            'details.product.images',
            'details.usedProduct.client',
            'details.usedProduct.category',
            'details.addons.addon',
            'details.attributes.attributeValue.attribute',
            'details.attributes.attributeValue.value',
            'address.city',
            'restaurant',
            'status',
            'rate.products.product',
        ];
    }

    private function generateOrderNumber(): string
    {
        $serial = 'ORD-';
        $today_orders_count = Order::whereDate('created_at', \Carbon\Carbon::today())->count() + 1;
        $serial .= 100 - date('y');
        $serial .= 100 - date('m');
        $serial .= 100 - date('d');
        $serial .= '-';
        $serial .= str_pad($today_orders_count, 4, '0', STR_PAD_LEFT);

        return $serial;
    }
}
