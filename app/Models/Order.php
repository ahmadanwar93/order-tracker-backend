<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // this is whitelist, that is only these can be assigned
    // $guarded is blacklist, everything except those in the array can be assigned
    // mass assignment allows to pass an array of attributes to create or update a model in one operation
    // the other way to update attribute (alternative of mass assignment) is manual property setting
    protected $fillable = [
        'customer_name',
        'status'
    ];

    // casting to enum such that can compare to enum directly
    // $order->status === OrderStatus::Delivered
    // instead of $order->status === OrderStatus::Delivered->values
    protected $casts = [
        'status' => OrderStatus::class
    ];

    // static function belongs to class, not instance
    // so we dont have to instantiate to invoke the method

    // self is current class (static context)
    // $thos is current instance (object)
    protected static function booted(): void
    {
        // use creating here because we need to modify the attributes before saving
        // any changes we made in creating will be included in the INSERT statement
        // use created when you want related events (like send email fires) AFTER the save is successful
        // use created when you need the saved id 
        static::creating(function (Order $order) {
            if (empty($order->references)) {
                $order->references = self::generateReference();
            }

            if (empty($order->status)) {
                $order->status = OrderStatus::Placed;
            }
        });
    }

    public function transitionTo(OrderStatus $status): void
    {
        // $this->status is OrderStatus enum instance hence can use the method defined in there directly
        if (!$this->status->canTransitionTo($status)) {
            // for this, we normal Exception is too generic
            // we can use more specific Exception for better semantic clarity
            // But in the catch block has to catch this specific Exception 
            throw new \InvalidArgumentException(
                "Cannot transition from {$this->status->value} to {$status->value}"
            );
        }

        $this->status = $status;
        $this->save();
    }

    private static function generateReference(): string
    {
        // This is not the best way to do this in the case of concurrency
        $lastOrder = self::latest('id')->first();
        $nextNumber = $lastOrder ? $lastOrder->id + 1 : 1;

        return 'ORD-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
