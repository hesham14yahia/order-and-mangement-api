<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => $this->faker->randomElement(OrderStatus::cases()),
            'total_amount' => 0,
            'user_id' => User::factory()->create()->id,
        ];
    }

    /**
     * Attach order items
     */
    public function withItems(int $count = 3): static
    {
        return $this->afterCreating(function (Order $order) use ($count) {
            $items = OrderItem::factory()
                ->count($count)
                ->create(['order_id' => $order->id]);

            $order->update([
                'total_amount' => $items->sum(fn ($item) => $item->price * $item->quantity),
            ]);
        });
    }
}
