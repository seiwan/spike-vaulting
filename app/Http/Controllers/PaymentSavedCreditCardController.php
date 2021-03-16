<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\PayPal\Vault\PaymentToken;

class PaymentSavedCreditCardController extends Controller
{
    public function index(PaymentToken $paymentToken)
    {
        $savedCreditCards = json_decode($paymentToken->getAll('customer_1'));

        return view('payment-saved-credit-cards', compact('savedCreditCards'));
    }
}
