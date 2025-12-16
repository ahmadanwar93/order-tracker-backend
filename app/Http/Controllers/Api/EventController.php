<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EventController extends Controller
{
    public function index(Request $request, Order $order): AnonymousResourceCollection
    {
        $query = $order->events()->orderBy('sequence');

        if ($request->has('after')) {
            // for this better use $request->integer compared to $request->input, for type safety
            $query->where('sequence', '>', $request->integer('after'));
        }

        // this would create an anonymous resource collection at runtime
        // alternative is to create dedicated collection resource for multiple models (use --collection)
        // better for DRY and also customization purposes
        return EventResource::collection($query->get());
    }
}
