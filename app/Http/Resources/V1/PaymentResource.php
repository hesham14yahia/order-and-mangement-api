<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'amount' => $this->amount,
            'status' => $this->status->label(),
            'payment_method' => $this->payment_method->label(),
            'transaction_reference' => $this->transaction_reference,
            'created_at' => $this->created_at->toDateTimeString(),
            'order' => OrderResource::make($this->whenLoaded('order')),
        ];
    }
}
