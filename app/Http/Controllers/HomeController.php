<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\PayPal\Vault\Token;

class HomeController extends Controller
{
    public function index(Token $paypalToken)
    {
        $paypalClientId = config('app.paypal.client_id');
        $paypalAccessToken = $paypalToken->getAccessToken();
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

        return view('welcome', compact('paypalClientId', 'paypalClientToken'));
    }

    public function saveCard(Request $request)
    {
        return response()->json(['status' => true, 'request' => $request->all()]);
    }
}
