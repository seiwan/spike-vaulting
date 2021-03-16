<?php

namespace App\Http\Services\PayPal\Vault;

class Token
{
    public function getAccessToken()
    {
        $curl = curl_init();

        // Get acces token PayPal
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api-m.sandbox.paypal.com/v1/oauth2/token',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
          CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
          CURLOPT_USERPWD => config('app.paypal.client_id') . ':' . config('app.paypal.client_secret'),
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
          ),
        ));

        // TO DO : Manage errors
        $paypalAccessToken = json_decode(curl_exec($curl))->access_token;

        curl_close($curl);

        return $paypalAccessToken;
    }
}
