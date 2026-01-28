<?php

namespace App\Services\V1;

use App\Models\Order;
use App\Models\Payment;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Symfony\Component\HttpKernel\Exception\HttpException;

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

    public function process(Order $order, PaymentMethod $method)
    {
        if ($order->status !== OrderStatus::CONFIRMED) {
            throw new HttpException(422, 'Only confirmed orders can be charged');
        }

        if ($order->payment) {
            throw new HttpException(422, 'Order already has a payment');
        }

        $result = $method->gateway()->charge([
            'amount' => $order->total_amount
        ]);

        if ($result['status'] !== PaymentStatus::SUCCESSFUL || empty($result['reference'])) {
            throw new HttpException(500, 'Payment failed');
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
