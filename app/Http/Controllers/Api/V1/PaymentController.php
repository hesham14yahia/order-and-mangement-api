<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Order;
use App\Services\V1\PaymentService;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\PaymentResource;
use App\Http\Requests\Api\V1\PaymentRequest;
use App\Http\Requests\Api\V1\PaymentFilterRequest;

class PaymentController extends Controller
{
    public function __construct(protected PaymentService $paymentService)
    {
        //
    }

    /**
     * list payments
     */
    public function index(PaymentFilterRequest $request)
    {
        $payments = $this->paymentService->getPaymentsPaginated($request->validated());

        return PaymentResource::collection($payments->load("order", "order.items"))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * payment charge for an order
     */
    public function charge(PaymentRequest $request, Order $order)
    {
        $payment = $this->paymentService->process($order, $request->validated()["payment_method"]);

        return (new PaymentResource($payment->load("order", "order.items")))
            ->response()
            ->setStatusCode(201);
    }
}
