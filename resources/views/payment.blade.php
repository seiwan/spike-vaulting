@extends('layouts.app')

@push('css')
    <!-- Sample CSS styles for demo purposes. You can override these styles to match your web page's branding. -->
    <link rel="stylesheet" type="text/css" href="https://www.paypalobjects.com/webstatic/en_US/developer/docs/css/cardfields.css"/>
@endpush

@push('js')
    <!-- PayPal SDK -->
    <script src="https://www.paypal.com/sdk/js?client-id={{ $paypalClientId }}&components=buttons,hosted-fields" data-client-token="{{ $paypalClientToken }}"></script>
    {{-- <script src="https://www.paypal.com/sdk/js?client-id={{ $paypalClientId }}&vault=true" data-client-token="{{ $paypalClientToken }}"></script> --}}
@endpush

@section('content')
<div class="max-w-6xl mx-auto sm:px-6 lg:px-8 p-5">

    <div id="paypal-button-container" width="350px"></div>

    @if($savedCreditCards->payment_tokens)
        <div class="text-center m-1 mb-2">
            <a href="{{ route('payment-saved-credit-card') }}" class="btn btn-primary">
                Use a saved credit card
            </a>
        </div>
    @endif

    <!-- Advanced credit and debit card payments form -->
    <div class="card_container text-left">
        <form id="card-form">
            <div>
              <input type="checkbox" id="vault" name="vault">
              <label for="vault">Save your card</label>
            </div>
            <label for="card-number">Card Number</label><div id="card-number" class="card_field"></div>
            <div>
             <label for="expiration-date">Expiration Date</label>
             <div id="expiration-date" class="card_field"></div>
            </div>
            <div>
             <label for="cvv">CVV</label><div id="cvv" class="card_field"></div>
            </div>
            <label for="card-holder-name">Name on Card</label>
            <input type="text" id="card-holder-name" name="card-holder-name" autocomplete="off" placeholder="card holder name"/>
            <div>
             <label for="card-billing-address-street">Billing Address</label>
             <input type="text" id="card-billing-address-street" name="card-billing-address-street" autocomplete="off" placeholder="street address"/>
            </div>
            <div>
             <label for="card-billing-address-unit">&nbsp;</label>
             <input type="text" id="card-billing-address-unit" name="card-billing-address-unit" autocomplete="off" placeholder="unit"/>
            </div>
            <div>
             <input type="text" id="card-billing-address-city" name="card-billing-address-city" autocomplete="off" placeholder="city"/>
            </div>
            <div>
             <input type="text" id="card-billing-address-state" name="card-billing-address-state" autocomplete="off" placeholder="state"/>
            </div>
            <div>
             <input type="text" id="card-billing-address-zip" name="card-billing-address-zip" autocomplete="off" placeholder="zip / postal code"/>
            </div>
            <div>
             <input type="text" id="card-billing-address-country" name="card-billing-address-country" autocomplete="off" placeholder="country code" />
            </div>
            <br><br>
            <button value="submit" id="submit" class="btn btn-primary">Pay</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        paypal.Buttons({
            onApprove: function(data, actions) {
                console.log(data);
                return actions.order.capture().then(function(details) {
                    alert('Transaction completed by ' + details.payer.name.given_name);
                    console.log(details);
                    window.axios({
                        method: 'post',
                        url: '/user',
                        data: {
                            capture: details
                        }
                    }).then(function (response) {
                        console.log(response);
                    }).catch(function (error) {
                        console.log(error);
                    });
                });
            //   return fetch('/my-server/get-paypal-transaction', {
            //     headers: {
            //       'content-type': 'application/json'
            //     },
            //     body: JSON.stringify({
            //       orderID: data.orderID
            //     })
            //   }).then(function(res) {
            //     return res.json();
            //   }).then(function(details) {
            //     alert('Transaction approved by ' + details.payer_given_name);
            //
            }
        }).render('#paypal-button-container');
        // This function displays Smart Payment Buttons on your web page.

        // If this returns false or the card fields aren't visible, see Step #1.
        if (paypal.HostedFields.isEligible()) {
            // Renders card fields
            paypal.HostedFields.render({
                // Call your server to set up the transaction
                createOrder: function () {
                    return fetch('paypal/checkout/order/create', {
                        method: 'post',
                        headers: {
                            'content-type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(function(res) {
                        console.log(res);

                        return res.json();
                    }).then(function(orderData) {
                        console.log(orderData);
                        orderId = orderData.result.id;
                        console.log(orderId);

                        return orderId;
                    });
                },
                styles: {
                    '.valid': {
                        'color': 'green'
                    },
                    '.invalid': {
                        'color': 'red'
                    }
                },

                fields: {
                    number: {
                        selector: "#card-number",
                        placeholder: "4111 1111 1111 1111"
                    },
                    cvv: {
                        selector: "#cvv",
                        placeholder: "123"
                    },
                    expirationDate: {
                        selector: "#expiration-date",
                        placeholder: "MM/YY"
                    }
                }
            }).then(function (cardFields) {
                // console.log('Vault :', document.querySelector('#vault').checked);
                document.querySelector("#card-form").addEventListener('submit', (event) => {
                event.preventDefault();
                console.log('Card Fields', cardFields);
                cardFields.submit({
                    vault: document.querySelector('#vault').checked,
                    // Cardholder's first and last name
                    cardholderName: document.getElementById('card-holder-name').value,
                    // Billing Address
                    billingAddress: {
                        // Street address, line 1
                        streetAddress: document.getElementById('card-billing-address-street').value,
                        // Street address, line 2 (Ex: Unit, Apartment, etc.)
                        extendedAddress: document.getElementById('card-billing-address-unit').value,
                        // State
                        region: document.getElementById('card-billing-address-state').value,
                        // City
                        locality: document.getElementById('card-billing-address-city').value,
                        // Postal Code
                        postalCode: document.getElementById('card-billing-address-zip').value,
                        // Country Code
                        countryCodeAlpha2: document.getElementById('card-billing-address-country').value
                    },
                }).then(function (payload) {
                    console.log('Payload', payload);
                    return fetch('paypal/checkout/order/capture/' + payload.orderId, {
                         method: 'post',
                         headers: {
                             'content-type': 'application/json',
                             'X-CSRF-TOKEN': '{{ csrf_token() }}'
                         }
                     }).then(function(res) {
                         console.log('Res :', res);

                         return res.json();
                     }).then(function(details) {
                         console.log('Details :', details);

                         alert('Transaction funds captured from ' + details.payer_given_name);
                         window.axios({
                             method: 'post',
                             url: '/paypal/vault/payment-token/create',
                             data: {
                                 capture: details
                             }
                         }).then(function (response) {
                             console.log(response);
                         }).catch(function (error) {
                             console.log(error);
                         });
                         // return fetch('paypal/vault/payment-token/create', {
                         //      method: 'post',
                         //      headers: {
                         //          'content-type': 'application/json',
                         //          'X-CSRF-TOKEN': '{{ csrf_token() }}'
                         //      }
                         //  }).then(function(res) {
                         //      console.log(res);
                         //
                         //      return res.json();
                         //  })
                      });
                     // Payment was successful! Show a notification or redirect to another page.
                    // window.location.replace('http://www.somesite.com/review');
                        // Make a call to capture the order (payload.orderId) here
                    });
                });
              });
        } else {
            // Hides card fields if the merchant isn't eligible
            document.querySelector("#card-form").style = 'display: none';
         }
    </script>
@endpush
