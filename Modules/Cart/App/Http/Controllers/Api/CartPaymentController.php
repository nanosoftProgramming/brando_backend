<?php

namespace Modules\Cart\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Modules\Common\Helpers\PaymobPaymentService;

class CartPaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymobPaymentService $paymentService)
    {
        $this->middleware('auth:client')->except('paymentCallback');
        $this->paymentService = $paymentService;
    }

    /**
     * Get payment summary before payment
     */
    public function getPaymentSummary()
    {
        try {
            $summary = $this->paymentService->getCartPaymentSummary();

            return returnMessage(true, 'Payment summary fetched', $summary);
        } catch (Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'error');
        }
    }

    /**
     * Initiate cart payment
     */
    public function initiatePayment(Request $request)
    {
        try {
            $client = auth('client')->user();
            $paymentData = $this->paymentService->createCartPayment($client);

            return returnMessage(true, 'Payment initiated successfully', [
                'payment_url' => $paymentData['payment_response']['redirect_url'] ?? null,  // ✅ Use redirect_url
                'payment_token' => $paymentData['payment_response']['clientSecret'] ?? null, // ✅ Use clientSecret
                'amount_egp' => $paymentData['amount'],
                'payment_id' => $paymentData['payment_response']['payment_id'] ?? null,
            ]);

        } catch (Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'payment_error');
        }
    }

    /**
     * Handle payment callback/webhook
     */
    public function paymentCallback(Request $request)
    {
        try {
            $verificationResult = $this->paymentService->verifyCartPayment($request);

            if ($verificationResult['success']) {
                return returnMessage(true, 'Payment successful - cart cleared', [
                    'payment_data' => $verificationResult['payment_data'],
                ]);
            }

            return returnMessage(false, 'Payment verification failed', null, 'payment_failed');

        } catch (Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'verification_error');
        }
    }
}
