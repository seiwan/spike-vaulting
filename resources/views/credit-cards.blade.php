@extends('layouts.app')

@section('content')
    <a href="{{ route('home') }}" class="btn btn-primary m-1">Home</a>

    <div class="card bg-light mb-3" style="max-width: 18rem;">
        <div class="card-header">My saved credit cards</div>

        <div class="card-body">
            <table class="table table-striped table-dark">
                <tr>
                    <td>Last digits</td>

                    <td>Brand</td>
                </tr>

                @foreach($creditCards->payment_tokens as $creditCard)
                    <tr>
                        <td>
                            {{ $creditCard->source->card->last_digits }}
                        </td>

                        <td>
                            {{ $creditCard->source->card->brand }}
                        </td>

                        <td>
                            <form action="{{ url('paypal/vault/payment-token/delete', ['paymentToken' => $creditCard->id]) }}" method="POST">
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
