<?php

namespace App\Interfaces;

interface PaymentGatewayInterface
{
    public function charge(array $data): array;
}
