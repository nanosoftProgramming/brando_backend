<?php

namespace Modules\Cart\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Modules\Cart\App\Http\Requests\CartRequest;
use Modules\Cart\App\Models\CartItem;
use Modules\Cart\App\Models\CartItemAddon;
use Modules\Cart\DTO\CartItemDto;
use Modules\Cart\Service\CartService;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->middleware('auth:client');
        $this->cartService = $cartService;
    }

    public function index(Request $request)
    {
        try {
            $cartData = $this->cartService->findAll();

            return returnMessage(true, 'Cart fetched successfully', $cartData);
        } catch (Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function store(CartRequest $request)
    {
        try {
            $cartItemDto = (new CartItemDto($request))->dataFromRequest();
            $validationErrors = (new CartItemDto($request))->validate();
            if (! empty($validationErrors)) {
                return returnMessage(false, implode(', ', $validationErrors), null, 'validation_error');
            }
            $cart = $this->cartService->create($cartItemDto);
            $cartData = $this->cartService->findAll();

            return returnMessage(true, 'Item added to cart successfully', $cartData);
        } catch (Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function update(CartRequest $request, CartItem $cartItem)
    {
        try {

            if ($cartItem->cart->client_id !== auth('client')->id()) {
                return returnMessage(false, 'Unauthorized access to cart item', null, 'unauthorized');
            }
            $cartItemDto = new CartItemDto($request);
            $updatedCartItem = $this->cartService->update($cartItem, $cartItemDto->dataFromRequest());
            $cartData = $this->cartService->findAll();

            return returnMessage(true, 'Cart item updated successfully', $cartData);
        } catch (Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function destroy(CartItem $cartItem)
    {
        try {
            if ($cartItem->cart->client_id !== auth('client')->id()) {
                return returnMessage(false, 'Unauthorized access to cart item', null, 'unauthorized');
            }
            $this->cartService->delete($cartItem);
            $cartData = $this->cartService->findAll();

            return returnMessage(true, 'Item removed from cart successfully', $cartData);
        } catch (Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        try {
            $this->cartService->clear();

            return returnMessage(true, 'Cart cleared successfully', [
                'items' => [],
                'summary' => [
                    'total_items' => 0,
                    'unique_products' => 0,
                    'total_price_egp' => 0,
                    'total_price_sar' => 0,
                    'restaurant' => [
                        'id' => null,
                        'name' => null,
                    ],
                    'is_empty' => true,
                ],
            ]);
        } catch (Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function increment(CartItem $cartItem)
    {
        try {
            if ($cartItem->cart->client_id !== auth('client')->id()) {
                return returnMessage(false, 'Unauthorized access to cart item', null, 'unauthorized');
            }
            $this->cartService->incrementCartItem($cartItem);
            $cartData = $this->cartService->findAll();

            return returnMessage(true, 'Cart item quantity incremented successfully', $cartData);
        } catch (Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function decrement(CartItem $cartItem)
    {
        try {
            if ($cartItem->cart->client_id !== auth('client')->id()) {
                return returnMessage(false, 'Unauthorized access to cart item', null, 'unauthorized');
            }
            $result = $this->cartService->decrementCartItem($cartItem);
            $cartData = $this->cartService->findAll();
            if ($result === true) {
                return returnMessage(true, 'Cart item removed (quantity was 1)', $cartData);
            }

            return returnMessage(true, 'Cart item quantity decremented successfully', $cartData);
        } catch (Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function incrementAddon(CartItemAddon $cartItemAddon)
    {
        try {
            if ($cartItemAddon->cartItem->cart->client_id !== auth('client')->id()) {
                return returnMessage(false, 'Unauthorized access to cart addon', null, 'unauthorized');
            }

            $this->cartService->incrementAddon($cartItemAddon);
            $cartData = $this->cartService->findAll();

            return returnMessage(true, 'Addon quantity incremented successfully', $cartData);
        } catch (Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function decrementAddon(CartItemAddon $cartItemAddon)
    {
        try {
            if ($cartItemAddon->cartItem->cart->client_id !== auth('client')->id()) {
                return returnMessage(false, 'Unauthorized access to cart addon', null, 'unauthorized');
            }
            $result = $this->cartService->decrementAddon($cartItemAddon);
            $cartData = $this->cartService->findAll();
            if ($result === true) {
                return returnMessage(true, 'Addon removed (quantity was 1)', $cartData);
            }

            return returnMessage(true, 'Addon quantity decremented successfully', $cartData);
        } catch (Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function summary()
    {
        try {
            $summary = $this->cartService->getCartSummary();

            return returnMessage(true, 'Cart summary fetched successfully', $summary);
        } catch (Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }
}
