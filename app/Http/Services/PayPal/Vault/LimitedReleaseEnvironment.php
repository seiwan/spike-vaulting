<?php

namespace App\Http\Services\PayPal\Vault;

use PayPalCheckoutSdk\Core\PayPalEnvironment;

class LimitedReleaseEnvironment extends PayPalEnvironment
{
    public function __construct($clientId, $clientSecret)
    {
        parent::__construct($clientId, $clientSecret);
    }

    public function baseUrl()
    {
        return "https://api-m.sandbox.paypal.com";
    }
}
