<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Placed = 'placed';
    case Paid = 'paid';
    case Processing = 'processing';
    case Shipped = 'shipped';
    case Delivered = 'delivered';

    // the return signature of the function means that we want to return an instance of the class, not the class itself
    // so, for the next() function, we are returning for example self::Paid which is an instance of this class which is just OrderStatus
    // remember that self::Paid is an instance of self which is OrderStatus
    public function next(): ?self
    {
        // one step forward in the chain
        // basically for this match function, I give in an instance of the enum class. OrderStatus::Paid

        // enum is a singleton. For example, self::Placed is an instance of OrderStatus
        return match ($this) {
            // here self is shorthand of the current class
            // so self::Placed is similar to OrderStatus::Placed
            self::Placed => self::Paid,
            self::Paid => self::Processing,
            self::Processing => self::Shipped,
            self::Shipped => self::Delivered,
            self::Delivered => null,
        };
    }

    public function canTransitionTo(self $status): bool
    {
        // can be used to check if from current state move to the target state with multiple steps
        // but it only ensures that the state is moving FORWARD
        // it does not guarantee that it is only one step
        $current = $this;
        // $this refers to the current enum case instance that the method was called on
        // enums are singleton, hence why can use === when comparing

        // $current can be null when we use next() on self::Delivered
        // this is to prevent infinite loop
        // and also to make sure the Delivered status is the final one in the state
        while ($current !== null) {
            if ($current === $status) {
                return true;
            }
            $current = $current->next();
        }

        return false;
    }
}
