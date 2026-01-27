<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'transaction_reference',
        'payment_method',
        'amount',
        'status',
        'order_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'status' => PaymentStatus::class,
        'payment_method' => PaymentMethod::class,
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
