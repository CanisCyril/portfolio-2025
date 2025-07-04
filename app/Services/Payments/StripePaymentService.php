<?php

namespace App\Services\Payments;
use App\Contracts\PaymentServiceInterface;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Exception\ApiErrorException;

class StripePaymentService implements PaymentServiceInterface
{
    protected $stripeSecretKey;

    public function __construct()
    {
        $this->stripeSecretKey = config('services.stripe.secret');
        Stripe::setApiKey($this->stripeSecretKey);
    }

    public function charge(int $amount, string $currency, string $token): bool
    {
        try {
            Charge::create([
                'amount' => $amount,
                'currency' => $currency,
                'source' => $token,
            ]);
            return true;
        } catch (ApiErrorException $e) {
            // Handle error appropriately
            return false;
        }
    }
}