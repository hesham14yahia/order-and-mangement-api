<?php

namespace App\Services\V1\Payment\Gateways;

use App\Enums\PaymentStatus;
use App\Interfaces\PaymentGatewayInterface;

class CreditCardGateway implements PaymentGatewayInterface
{
    public function charge(array $data): array
    {
        $config = config("payments.gateways.credit_card");

        return [
            'status' => PaymentStatus::SUCCESSFUL,
            'reference' => 'CC-' . uniqid()
        ];
    }
}
