<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Services\V1\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentChargeTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_charge_equals_order_total_amount(): void
    {
        $testAmounts = [50.00, 150.75, 999.99, 1000.00, 100, 40, 0.20];

        foreach ($testAmounts as $amount) {
            foreach (PaymentMethod::cases() as $method) {
                $user = User::factory()->create();

                $order = Order::factory()
                    ->for($user)
                    ->state([
                        'status' => OrderStatus::CONFIRMED,
                        'total_amount' => $amount,
                    ])
                    ->create();

                $paymentService = new PaymentService();
                $payment = $paymentService->process($order, $method);

                $this->assertEquals($amount, $payment->amount);
                $this->assertEquals($order->total_amount, $payment->amount);
            }
        }
    }
}
