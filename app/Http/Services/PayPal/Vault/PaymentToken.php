<?php

namespace App\Http\Services\PayPal\Vault;

use App\Http\Services\PayPal\Vault\Token;

class PaymentToken
{
    /**
     * PayPal client access token
     *
     * @var App\Http\Services\PayPal\Vault\Token $token
     */
    protected $token;

    /**
     * Create a new credit card instance instance.
     *
     * @param  App\Http\Services\PayPal\Vault\Token  $token
     * @return void
     */
    public function __construct(Token $token)
    {
        $this->token = $token;
    }

    // public function store($creditCard)
    // {
    //     $curl = curl_init();
    //
    //     curl_setopt_array(
    //         $curl,
    //         [
    //             CURLOPT_URL => 'https://api-m.sandbox.paypal.com/v1/vault/credit-cards',
    //             CURLOPT_RETURNTRANSFER => true,
    //             CURLOPT_ENCODING => '',
    //             CURLOPT_MAXREDIRS => 10,
    //             CURLOPT_TIMEOUT => 0,
    //             CURLOPT_FOLLOWLOCATION => true,
    //             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //             CURLOPT_CUSTOMREQUEST => 'POST',
    //             CURLOPT_HTTPHEADER => [
    //                 'Accept: application/json',
    //                 'Accept-Language: en_US',
    //                 'Content-Type: application/json',
    //                 'Authorization: Bearer ' . $this->token->getAccessToken()
    //             ],
    //             CURLOPT_POSTFIELDS => $creditCard
    //         ]
    //     );
    //
    //     $response = curl_exec($curl);
    //
    //     curl_close($curl);
    //
    //     return $response;
    // }

    /** Get all payment tokens by customer id
     *
     * @param string $customerId
     */
    public function getAll($customerId)
    {
        $curl = curl_init();

        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL => 'https://api-m.sandbox.paypal.com/v2/vault/payment-tokens?customer_id=' . $customerId,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => [
                    'Accept: application/json',
                    'Accept-Language: en_US',
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $this->token->getAccessToken()
                ]
            ]
        );

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }

    /**
     * Create a payment token for a customer
     *
     * @param array $data
     */
    public function create($data)
    {
        $curl = curl_init();

        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL => 'https://api-m.sandbox.paypal.com/v2/vault/payment-tokens',
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
                    'Authorization: Bearer ' . $this->token->getAccessToken()
                ],
                CURLOPT_POSTFIELDS => urldecode(http_build_query([
                    'customer_id' => 'customer_1',
                    'card' => [
                        'number' => '4111111111111111',
                        'expiry' => '2021-05',
                        'name' => 'John Doe',
                        'billing_address' => [
                            'address_line_1' => '2211 N First Street',
                            'address_line_2' => '17.3.160',
                            'admin_area_1' =>  'CA',
                            'admin_area_2' => 'San Jose',
                            'postal_code' => '95131',
                            'country_code' => 'US'
                        ]
                    ]
                ]))
            ]
        );

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }

    /**
     * Delete a payment token
     *
     * @param array $paymentToken
     */
    public function delete($paymentToken)
    {
        $curl = curl_init();

        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL => 'https://api-m.sandbox.paypal.com/v2/vault/payment-tokens',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => [
                    'Accept: application/json',
                    'Accept-Language: en_US',
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $this->token->getAccessToken()
                ],
                CURLOPT_POSTFIELDS => [
                    'customer_id' => 'customer_1',
                    'source' => [
                        'brand' => 'VISA',
                        'card_type' => 'VISA',
                        'last_digits' => '8116',
                        'type' => 'UNKWOwN'
                    ]
                ]
            ]
        );

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }
}
