<?php

namespace App\Http\Controllers\PayPal\Checkout;

use App\Http\Controllers\Controller;
use App\Http\Services\PayPal\Checkout\Capture;
use Illuminate\Http\Request;

class CaptureController extends Controller
{
    public function get($captureId)
    {
        return response()->json(Capture::get($captureId));
    }
}
