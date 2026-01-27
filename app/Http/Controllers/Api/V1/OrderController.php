<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\V1\OrderResource;
use App\Services\V1\OrderService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CreateOrderRequest;

class OrderController extends Controller
{
    public function __construct(protected OrderService $orderService)
    {
        //
    }

    /**
     * create order
     */
    public function create(CreateOrderRequest $request)
    {
        $order = $this->orderService->createOrder($request->validated());

        return (new OrderResource($order->load('items')))
            ->response()
            ->setStatusCode(201);
    }
}
