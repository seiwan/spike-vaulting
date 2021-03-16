<?php

namespace App\Http\Services\PayPal\Checkout;

use App\Http\Services\PayPal\Checkout\PayPalClient;
use PayPalCheckoutSdk\Payments\CapturesGetRequest;

class Capture
{
    public static function get($captureId)
    {
        $client = PayPalClient::client();
        $response = $client->execute(new CapturesGetRequest($captureId));

        return $response;
    }
}
