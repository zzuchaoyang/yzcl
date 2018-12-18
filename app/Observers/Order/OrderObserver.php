<?php

namespace App\Observers\Order;

use App\Models\Order\Order;

class OrderObserver
{
    public function created(Order $order)
    {
        if (empty($order->order_number)) {
            $order->order_number = generateUuid('POP', $order->id);
            $order->save();
        }
    }
}
