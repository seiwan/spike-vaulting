<?php

namespace App\Http\Services\PayPal\Checkout;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use LimitedReleaseEnvironment;

ini_set('error_reporting', E_ALL); // or error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

class PayPalClient
{
    /**
     * Returns PayPal HTTP client instance with environment that has access
     * credentials context. Use this instance to invoke PayPal APIs, provided the
     * credentials have access.
     */
    public static function client()
    {
        return new PayPalHttpClient(self::environment());
    }

    /**
     * Set up and return PayPal PHP SDK environment with PayPal access credentials.
     * This sample uses SandboxEnvironment. In production, use LiveEnvironment.
     */
    public static function environment()
    {
        $clientId = config('app.paypal.client_id');
        $clientSecret = config('app.paypal.client_secret');
        return new LimitedReleaseEnvironment($clientId, $clientSecret);
    }
}
