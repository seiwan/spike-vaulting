<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\PayPal\Vault\Token;
use App\Http\Services\PayPal\Vault\PaymentToken;

class PaymentController extends Controller
{
    public function index(Token $token, PaymentToken $paymentToken)
    {
        $savedCreditCards = json_decode($paymentToken->getAll('customer_1'));
        $paypalClientId = config('app.paypal.client_id');
        $paypalAccessToken = $token->getAccessToken();
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api-m.sandbox.paypal.com/v1/identity/generate-token?customer_id=customer_1',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Accept-Language: en_US',
            'Content-Type: application/json',
            'Authorization: Bearer ' . $paypalAccessToken
          ),
        ));

        $paypalClientToken = json_decode(curl_exec($curl))->client_token;

        curl_close($curl);

        return view('payment', compact('paypalClientId', 'paypalClientToken', 'savedCreditCards'));
    }
}
