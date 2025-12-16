<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderEvent;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class EventService
{
    public function recordEvent(Order $order, string $eventType, array $payload): OrderEvent
    {
        $nextSequence = OrderEvent::where('order_id', $order->id)
            ->max('sequence') ?? 0;
        // max() is a query builder aggregates like count(), sum()
        // it all executes SQL immediately without needing get() or first()
        $nextSequence += 1;

        return OrderEvent::create([
            'uuid' => Str::uuid(),
            'order_id' => $order->id,
            'sequence' => $nextSequence,
            'event_type' => $eventType,
            'payload' => $payload,
        ]);
    }

    public function getEventsForOrder(Order $order): Collection
    {
        return $order->events()
            // here events() means we are constructing the query
            // events if the data already loaded (use it if the events data already loaded so there is no extra query)
            ->orderBy('sequence')
            ->get();
    }

    public function getEventsAfterSequence(Order $order, int $sequence): Collection
    {
        return $order->events()
            ->afterSequence($sequence)
            ->orderBy('sequence')
            ->get();
    }
}
