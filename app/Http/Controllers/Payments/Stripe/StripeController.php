<?php

namespace App\Http\Controllers\Payments\Stripe;

use App\Contracts\PaymentServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class StripeController extends Controller
{
    protected PaymentServiceInterface $paymentService;

    public function __construct(PaymentServiceInterface $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function pay(Request $request)
    {
        $success = $this->paymentService->charge(
            amount: 100, // e.g., $50.00 in cents
            currency: 'gbp', // GBP currency
            token: $request->input('stripeToken')
        );

        if ($success) {
            return redirect()->back()->with('message', 'Payment successful!');
        }

        return redirect()->back()->withErrors(['message' => 'Payment failed.']);
    }
}
