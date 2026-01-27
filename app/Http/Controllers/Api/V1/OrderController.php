<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Order;
use App\Services\V1\OrderService;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\OrderResource;
use App\Http\Requests\Api\V1\OrderRequest;

class OrderController extends Controller
{
    public function __construct(protected OrderService $orderService)
    {
        //
    }

    /**
     * create order
     */
    public function create(OrderRequest $request)
    {
        $order = $this->orderService->createOrder($request->validated());

        return (new OrderResource($order->load('items')))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * update order
     */
    public function update(OrderRequest $request, Order $order)
    {
        $order = $this->orderService->updateOrder($order, $request->validated());

        return (new OrderResource($order->load('items')))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * confirm order
     */
    public function confirm(Order $order)
    {
        $order = $this->orderService->confirmOrder($order);

        return (new OrderResource($order->load('items')))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * cancel order
     */
    public function cancel(Order $order)
    {
        $order = $this->orderService->cancelOrder($order);

        return (new OrderResource($order->load('items')))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * delete order
     */
    public function destroy(Order $order)
    {
        $this->orderService->deleteOrder($order);

        return response()->json([
            'message' => 'Order deleted successfully',
        ], 200);
    }
}
