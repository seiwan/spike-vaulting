<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\PayPal\Vault\PaymentToken;

class CreditCardsController extends Controller
{
    public function index(PaymentToken $paypalPaymentToken)
    {
        $creditCards = json_decode($paypalPaymentToken->getAll('customer_1'));

        return view('credit-cards', compact('creditCards'));
    }
}
