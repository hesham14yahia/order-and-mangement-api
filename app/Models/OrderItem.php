<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'product_name',
        'quantity',
        'price',
        'order_id',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
