<?php

namespace App\Services\V1\Payment\Gateways;

use App\Enums\PaymentStatus;
use App\Interfaces\PaymentGatewayInterface;

class PayPalGateway implements PaymentGatewayInterface
{
    public function charge(array $data): array
    {
        $config = config("payments.gateways.paypal");

        return [
            'status' => PaymentStatus::SUCCESSFUL,
            'reference' => 'PP-' . uniqid()
        ];
    }
}
