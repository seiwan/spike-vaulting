@extends('layouts.app')

@section('content')
<a href="{{ route('payment') }}" class="btn btn-primary m-1">Cancel</a>

<div class="card bg-light m-3">
    <div class="card-header">Select a credit card</div>

    <div class="card-body text-left">
        {{-- <form id="payment-saved-card" class="text-center"> --}}
        <div class="text-center">
            @foreach($savedCreditCards->payment_tokens as $key => $savedCreditCard)
                <div class="custom-control custom-radio">
                    <input type="radio" id="payment-token-{{ $key }}" name="payment-token" value="{{ $savedCreditCard->id }}">
                    <label for="payment-token">
                        XXXX-XXXX-XXXX-{{ $savedCreditCard->source->card->last_digits }} / {{ $savedCreditCard->source->card->brand }}
                    </label>
                </div>
            @endforeach
            {{-- <div class="custom-control custom-radio">
                <input type="radio" id="payment-token-1" name="payment-token" value="bilouche">
                <label for="payment-token">
                    XXXX-XXXX-XXXX-
                </label>
            </div> --}}
            <div class="form-group">
                <button id="pay" class="btn btn-success">
                    Pay
                </button>
            </div>
        </div>
        {{-- </form> --}}

    </div>
</div>
@endsection

@push('scripts')
    <script>
        $('#pay').on('click', function() {
            console.log($("input[name=payment-token]").val());
            window.axios({
                method: 'post',
                url: 'paypal/vault/order/create',
                data: {
                    paymentToken: $("input[name=payment-token]").val()
                }
            }).then(function (response) {
                console.log(response);
            }).catch(function (error) {
                console.log(error);
            });
        })
    </script>
@endpush
