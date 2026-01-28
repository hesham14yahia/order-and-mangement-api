<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected string $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('api_token')->plainTextToken;
    }

    /**
     * Test get list of payments
     */
    public function test_get_payments_list(): void
    {
        $response = $this->getJson('/api/v1/payments', [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'status',
                    'amount',
                    'payment_method',
                ]
            ]
        ]);
    }

    public function test_get_payments_list_fails_without_authentication(): void
    {
        $response = $this->getJson('/api/v1/payments');

        $response->assertStatus(401);
    }

    public function test_charge_payment_with_valid_payment_method(): void
    {
        $order = Order::factory()->for($this->user)->create([
            'status' => OrderStatus::CONFIRMED,
            'total_amount' => 99.99,
        ]);

        $response = $this->postJson("/api/v1/payments/charge/{$order->id}", [
            'payment_method' => PaymentMethod::CREDIT_CARD->value,
        ], [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'status',
                'amount',
                'payment_method',
            ]
        ]);

        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'amount' => $order->total_amount,
        ]);
    }

    public function test_charge_payment_with_all_payment_methods(): void
    {
        foreach (PaymentMethod::cases() as $paymentMethod) {
            $order = Order::factory()->for($this->user)->create([
                'status' => OrderStatus::CONFIRMED,
            ]);

            $response = $this->postJson("/api/v1/payments/charge/{$order->id}", [
                'payment_method' => $paymentMethod->value,
            ], [
                'Authorization' => "Bearer {$this->token}",
            ]);

            $response->assertStatus(201);
            $response->assertJsonPath('data.payment_method', $paymentMethod->label());
        }
    }

    public function test_charge_payment_fails_with_invalid_payment_method(): void
    {
        $order = Order::factory()->for($this->user)->create([
            'status' => OrderStatus::CONFIRMED,
        ]);

        $response = $this->postJson("/api/v1/payments/charge/{$order->id}", [
            'payment_method' => 'INVALID_METHOD',
        ], [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('payment_method');
    }

    public function test_charge_payment_fails_with_missing_payment_method(): void
    {
        $order = Order::factory()->for($this->user)->create([
            'status' => OrderStatus::CONFIRMED,
        ]);

        $response = $this->postJson("/api/v1/payments/charge/{$order->id}", [], [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('payment_method');
    }

    public function test_charge_payment_for_non_existent_order(): void
    {
        $response = $this->postJson("/api/v1/payments/charge/99999", [
            'payment_method' => PaymentMethod::CREDIT_CARD->value,
        ], [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertStatus(404);
    }

    public function test_payment_operations_fail_without_authentication(): void
    {
        $response = $this->getJson('/api/v1/payments');
        $response->assertStatus(401);

        $order = Order::factory()->for($this->user)->create([
            'status' => OrderStatus::CONFIRMED,
        ]);
        $response = $this->postJson("/api/v1/payments/charge/{$order->id}", [
            'payment_method' => PaymentMethod::CREDIT_CARD->value,
        ]);
        $response->assertStatus(401);
    }

    public function test_payment_operations_fail_with_not_confirmed_order(): void
    {
        foreach (OrderStatus::cases() as $status) {
            if ($status === OrderStatus::CONFIRMED) {
                continue;
            }

            $order = Order::factory()->for($this->user)->create([
                'status' => $status,
            ]);

            $response = $this->postJson("/api/v1/payments/charge/{$order->id}", [
                'payment_method' => PaymentMethod::CREDIT_CARD->value,
            ], [
                'Authorization' => "Bearer {$this->token}",
            ]);

            $response->assertStatus(500);
            $response->assertJsonPath('message', 'Only confirmed orders can be charged');
        }
    }

    public function test_payment_operations_fail_if_order_already_has_payment(): void
    {
        $order = Order::factory()->for($this->user)->create([
            'status' => OrderStatus::CONFIRMED,
        ]);

        // First payment
        $this->postJson("/api/v1/payments/charge/{$order->id}", [
            'payment_method' => PaymentMethod::CREDIT_CARD->value,
        ], [
            'Authorization' => "Bearer {$this->token}",
        ]);

        // Second payment attempt
        $response = $this->postJson("/api/v1/payments/charge/{$order->id}", [
            'payment_method' => PaymentMethod::CREDIT_CARD->value,
        ], [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertStatus(422);
        $response->assertJsonPath('message', 'Order already has a payment');
    }
}
