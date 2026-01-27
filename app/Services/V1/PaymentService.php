<?php

namespace App\Services\V1;

use App\Models\Order;
use App\Models\Payment;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Services\V1\Payment\PaymentGatewayFactory;

class PaymentService
{
    public function getPaymentsPaginated(array $filters = [])
    {
        $query = Payment::query();

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->paginate(10);
    }

    public function process(Order $order, string $method)
    {
        if ($order->status !== OrderStatus::CONFIRMED) {
            throw new \Exception('Order must be confirmed');
        }

        $gateway = PaymentGatewayFactory::make($method);
        $result = $gateway->charge([
            'amount' => $order->total_amount
        ]);

        if ($result['status'] !== PaymentStatus::SUCCESSFUL || empty($result['reference'])) {
            throw new \Exception('Payment failed');
        }

        return Payment::create([
            'transaction_reference' => $result['reference'],
            'payment_method' => $method,
            'amount' => $order->total_amount,
            'status' => $result['status'],
            'order_id' => $order->id,
        ]);
    }
}
