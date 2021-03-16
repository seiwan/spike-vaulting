<?php

namespace App\Http\Controllers\PayPal\Vault;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\PayPal\Vault\Order;

class OrderController extends Controller
{
    /**
     * Create a PayPal order with a payment token
     *
     * @param $paymentToken
     *
     */
    public function create(Request $request, Order $order)
    {
        return response()->json($order->create($request->paymentToken));
    }
}
