<?php

namespace App\Enums;

use App\Services\V1\Payment\Gateways\CreditCardGateway;
use App\Services\V1\Payment\Gateways\PayPalGateway;

enum PaymentMethod: string
{
    // credit_card, paypal
    case CREDIT_CARD = 'credit_card';
    case PAYPAL = 'paypal';

    public function label()
    {
        return match ($this) {
            self::CREDIT_CARD => 'Credit Card',
            self::PAYPAL => 'PayPal',
        };
    }

    public function gateway()
    {
        return match ($this) {
            self::CREDIT_CARD => new CreditCardGateway(),
            self::PAYPAL => new PayPalGateway(),
        };
    }

    public function isCreditCard(): bool
    {
        return $this === self::CREDIT_CARD;
    }

    public function isPaypal(): bool
    {
        return $this === self::PAYPAL;
    }
}
