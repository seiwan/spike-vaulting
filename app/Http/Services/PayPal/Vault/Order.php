<?php

namespace App\Http\Services\PayPal\Vault;

use App\Http\Services\PayPal\Vault\Token;

class Order
{
    /**
     * PayPal client access token
     *
     * @var App\Http\Services\PayPal\Vault\Token $token
     */
    protected $token;

    /**
     * Create a new Vault Order instance.
     *
     * @param  App\Http\Services\PayPal\Vault\Token  $token
     * @return void
     */
    public function __construct(Token $token)
    {
        $this->token = $token;
    }

    /** Create an order with a payment token
     *
     * @param string $paymentToken
     */
    public function create($paymentToken)
    {
        $data = json_encode([
            'intent' => 'CAPTURE',
            'payment_source' => [
                'token' => [
                    'type' => 'PAYMENT_METHOD_TOKEN',
                    'id' => $paymentToken
                ]
            ],
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => 'EUR',
                        'value' => '10.00'
                    ]
                ]
            ]
        ], JSON_UNESCAPED_SLASHES);

        $curl = curl_init();

        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL => 'https://api-m.sandbox.paypal.com/v2/checkout/orders',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_HTTPHEADER => [
                    'Accept: application/json',
                    'Accept-Language: en_US',
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $this->token->getAccessToken(),
                    'PayPal-Request-Id: ' . uniqid()
                ]
            ]
        );
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }
}
