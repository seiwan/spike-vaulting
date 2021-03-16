@extends('layouts.app')

@section('content')
    <a href="{{ route('payment') }}" class="btn btn-primary m-1">Payment Test</a>

    <a href="{{ route('credit-cards') }}" class="btn btn-primary m-1">View my saved credit cards</a>
@endsection
