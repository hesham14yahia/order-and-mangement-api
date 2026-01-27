<?php

namespace App\Services\V1\Payment;

use App\Interfaces\PaymentGatewayInterface;
use App\Services\V1\Payment\Gateways\PayPalGateway;
use App\Services\V1\Payment\Gateways\CreditCardGateway;

class PaymentGatewayFactory
{
    public static function make(string $method): PaymentGatewayInterface
    {
        return match ($method) {
            'credit_card' => new CreditCardGateway(),
            'paypal' => new PayPalGateway(),
            default => throw new \Exception('Unsupported payment method'),
        };
    }
}

