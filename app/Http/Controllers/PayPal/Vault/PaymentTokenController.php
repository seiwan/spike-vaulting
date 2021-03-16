<?php

namespace App\Http\Controllers\PayPal\Vault;

use App\Http\Controllers\Controller;
use App\Http\Services\PayPal\Vault\PaymentToken;
use Illuminate\Http\Request;

class PaymentTokenController extends Controller
{
    // public function store(Request $request)
    // {
    //     return response()->json(CreditCard::store());
    // }

    public function getAll($customerId, PaymentToken $paymentToken)
    {
        return response()->json(json_decode($paymentToken->getAll($customerId)));
    }

    /**
     * Create payment token for a customer
     *
     * @param Illuminate\Http\Request $request
     */
    public function create(Request $request, PaymentToken $paymentToken)
    {
        return response()->json($paymentToken->create($request->all()));
    }
}
