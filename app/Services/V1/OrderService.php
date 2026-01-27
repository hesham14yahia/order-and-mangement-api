<?php

namespace App\Services\V1;

use App\Models\Order;
use App\Enums\OrderStatus;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function createOrder(array $data)
    {
        if (!key_exists("items", $data) || empty($data["items"])) {
            throw new \Exception("Order must have at least one item.");
        }
        return DB::transaction(function () use ($data) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'status' => OrderStatus::PENDING,
                'total_amount' => collect($data['items'])->sum(function ($item) {
                    return $item['price'] * $item['quantity'];
                }),
            ]);
            $order->items()->createMany($data['items']);
            return $order;
        });
    }

    public function updateOrder(Order $order, array $data)
    {
        if (!key_exists("items", $data) || empty($data["items"])) {
            throw new \Exception("Order must have at least one item.");
        }
        return DB::transaction(function () use ($order, $data) {
            $order->update([
                'status' => $data['status'] ?? $order->status,
                'total_amount' => collect($data['items'])->sum(function ($item) {
                    return $item['price'] * $item['quantity'];
                }),
            ]);
            $order->items()->delete();
            $order->items()->createMany($data['items']);
            return $order;
        });
    }

    public function confirmOrder(Order $order)
    {
        $order->update(['status' => OrderStatus::CONFIRMED]);
        return $order;
    }
}
