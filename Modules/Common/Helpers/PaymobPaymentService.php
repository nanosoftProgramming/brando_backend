<?php

namespace Modules\Common\Helpers;

use Exception;
use Modules\Cart\Service\CartService;
use Nafezly\Payments\Classes\PaymobPayment;

class PaymobPaymentService
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Create payment for cart items
     */
    public function createCartPayment($client)
    {
        // Get cart summary
        $cartSummary = $this->cartService->getCartSummary();
        if ($cartSummary['is_empty']) {
            throw new Exception('Cart is empty');
        }
        $paymentAmount = $cartSummary['total_price_egp'];

        if ($paymentAmount <= 0) {
            throw new Exception('Invalid payment amount');
        }

        // Create Paymob payment
        $payment = new PaymobPayment;
        $response = $payment
            ->setUserFirstName($client->first_name ?? $client->name)
            ->setUserLastName($client->last_name ?? $client->name)
            ->setUserEmail($client->email)
            ->setUserPhone($client->phone)
            ->setAmount($paymentAmount)
            ->pay();

        // Add this line to see what Paymob actually returns
        \Log::info('Paymob Full Response:', $response);

        return [
            'payment_response' => $response,
            'amount' => $paymentAmount,
            'cart_summary' => $cartSummary,
            'client_id' => $client->id,
        ];
    }

    /**
     * Verify payment and clear cart
     */
    public function verifyCartPayment($request)
    {
        $payment = new PaymobPayment;
        $result = $payment->verify($request);

        if ($result['success']) {
            // Payment successful - clear the cart
            $this->cartService->clear();

            return [
                'success' => true,
                'payment_data' => [
                    'payment_id' => $result['payment_id'],
                    'amount' => number_format($result['process_data']['amount_cents'] / 100, 2),
                    'currency' => $result['process_data']['currency'],
                    'transaction_id' => $result['process_data']['id'],
                    'source_type' => $result['process_data']['source_data_type'],
                    'source_sub_type' => $result['process_data']['source_data_sub_type'],
                ],
                'result' => $result,
            ];
        }

        return [
            'success' => false,
            'result' => $result,
        ];
    }

    /**
     * Get cart payment summary (for display before payment)
     */
    public function getCartPaymentSummary()
    {
        $cartSummary = $this->cartService->getCartSummary();

        return [
            'amount_egp' => $cartSummary['total_price_egp'],
            'amount_sar' => $cartSummary['total_price_sar'],
            'items_count' => $cartSummary['total_items'],
            'restaurants' => $cartSummary['restaurants'],
            'is_empty' => $cartSummary['is_empty'],
        ];
    }
}
