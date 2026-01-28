<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'status' => $this->status->label(),
            'total_amount' => $this->total_amount,
            'created_at' => $this->created_at->toDateTimeString(),
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'payment' => PaymentResource::make($this->whenLoaded('payment')),
        ];
    }
}
