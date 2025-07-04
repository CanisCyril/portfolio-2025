<?php

namespace App\Contracts\Contracts;

interface PaymentServiceInterface
{
    public function charge(int $amount, string $currency, string $token): bool;
}
