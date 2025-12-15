<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $orders = Order::latest()->get();

        // whether to chain with ->response() or not?
        // chain it to explicitly return JsonResponse
        // chain it to further chain method like setStatusCode and header
        return OrderResource::collection($orders)->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateOrderRequest $request)
    {
        $order = Order::create($request->validated());

        // semantically correct to return 201 since a new resource has been created
        return (new OrderResource($order))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order): OrderResource
    {
        return new OrderResource($order);
    }

    public function transition(Order $order): OrderResource
    {
        $nextStatus = $order->status->next();
        // will return null if at the final status

        if (!$nextStatus) {
            abort(400, 'Order is already at final status');
        }

        $order->transitionTo($nextStatus);

        return new OrderResource($order);
    }
}
