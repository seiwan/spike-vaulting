<?php

namespace App\Http\Services\PayPal\Checkout;

// require __DIR__ . '/vendor/autoload.php';
//1. Import the PayPal SDK client that was created in `Set up Server-Side SDK`.
use App\Http\Services\PayPal\Checkout\PayPalClient;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;

class Order
{

    // 2. Set up your server to receive a call from the client
    /**
   *This is the sample function to create an order. It uses the
   *JSON body returned by buildRequestBody() to create an order.
   */
    public static function create($debug=false)
    {
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = self::buildRequestBody();
        // 3. Call PayPal to set up a transaction
        $client = PayPalClient::client();
        $response = $client->execute($request);

        if ($debug) {
            print "Status Code: {$response->statusCode}\n";
            print "Status: {$response->result->status}\n";
            print "Order ID: {$response->result->id}\n";
            print "Intent: {$response->result->intent}\n";
            print "Links:\n";

            foreach($response->result->links as $link) {
            print "\t{$link->rel}: {$link->href}\tCall Type: {$link->method}\n";
            }

            // To print the whole response body, uncomment the following line
            // echo json_encode($response->result, JSON_PRETTY_PRINT);
        }

        // 4. Return a successful response to the client.
        return $response;
    }

    /**
     *You can use this function to retrieve an order by passing order ID as an argument.
     */
    public static function get($orderId)
    {
        // 3. Call PayPal to get the transaction details
        $client = PayPalClient::client();
        $response = $client->execute(new OrdersGetRequest($orderId));

        return $response;
        /**
         *Enable the following line to print complete response as JSON.
         */
        //print json_encode($response->result);
        // print "Status Code: {$response->statusCode}\n";
        // print "Status: {$response->result->status}\n";
        // print "Order ID: {$response->result->id}\n";
        // print "Intent: {$response->result->intent}\n";
        // print "Links:\n";
        // foreach($response->result->links as $link)
        // {
        //   print "\t{$link->rel}: {$link->href}\tCall Type: {$link->method}\n";
        // }
        // // 4. Save the transaction in your database. Implement logic to save transaction to your database for future reference.
        // print "Gross Amount: {$response->result->purchase_units[0]->amount->currency_code} {$response->result->purchase_units[0]->amount->value}\n";

        // To print the whole response body, uncomment the following line
        // echo json_encode($response->result, JSON_PRETTY_PRINT);
    }

    /**
     *This function can be used to capture an order payment by passing the approved
     *order ID as argument.
     *
     *@param orderId
     *@param debug
     *@returns
     */
    public static function capture($orderId, $debug=false)
    {
        $request = new OrdersCaptureRequest($orderId);

        // 3. Call PayPal to capture an authorization
        $client = PayPalClient::client();
        $response = $client->execute($request);
        // 4. Save the capture ID to your database. Implement logic to save capture to your database for future reference.
        if ($debug) {
          print "Status Code: {$response->statusCode}\n";
          print "Status: {$response->result->status}\n";
          print "Order ID: {$response->result->id}\n";
          print "Links:\n";

          foreach($response->result->links as $link) {
            print "\t{$link->rel}: {$link->href}\tCall Type: {$link->method}\n";
          }
          print "Capture Ids:\n";

          foreach($response->result->purchase_units as $purchase_unit) {
            foreach($purchase_unit->payments->captures as $capture) {
              print "\t{$capture->id}";
            }
          }
          // To print the whole response body, uncomment the following line
          // echo json_encode($response->result, JSON_PRETTY_PRINT);
        }

        return $response;
    }

  /**
     * Setting up the JSON request body for creating the order with minimum request body. The intent in the
     * request body should be "AUTHORIZE" for authorize intent flow.
     *
     */
    private static function buildRequestBody()
    {
        return [
            'intent' => 'CAPTURE',
            'application_context' => [
                'return_url' => config('app.url'),
                'cancel_url' => config('app.url') . '/cancel'
            ],
            'purchase_units' => [
                0 => [
                    'amount' => [
                        'currency_code' => 'EUR',
                        'value' => '5.00'
                    ]
                ]
            ]
        ];
    }
}


/**
 *This is the driver function that invokes the createOrder function to create
 *a sample order.
 */
if (!count(debug_backtrace()))
{
  Order::create(true);
}
?>
