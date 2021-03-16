<?php

namespace App\Http\Controllers\PayPal\Checkout;

use App\Http\Controllers\Controller;
use App\Http\Services\PayPal\Checkout\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Create a PayPal order
     *
     */
    public function create()
    {
        return response()->json(Order::create());
    }

    public function get($orderId)
    {
        return response()->json(Order::get($orderId));
    }

    public function capture($orderId)
    {
        return response()->json(Order::capture($orderId));
    }
}
