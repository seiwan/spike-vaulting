<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CancelController;
use App\Http\Controllers\CreditCardsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentSavedCreditCardController;
use App\Http\Controllers\PayPal\Checkout\OrderController as CheckoutOrderController;
use App\Http\Controllers\PayPal\Checkout\CaptureController;
use App\Http\Controllers\PayPal\Vault\PaymentTokenController;
use App\Http\Controllers\PayPal\Vault\OrderController as VaultOrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('payment', [PaymentController::class, 'index'])->name('payment');
Route::get('payment-saved-credit-card', [PaymentSavedCreditCardController::class, 'index'])->name('payment-saved-credit-card');
Route::get('credit-cards', [CreditCardsController::class, 'index'])->name('credit-cards');
Route::get('cancel', [CancelController::class, 'show'])->name('cancel');

Route::post('user', [HomeController::class, 'saveCard']);

// PayPal Checkout API
Route::prefix('paypal/checkout')->name('paypal.checkout.')->group(function() {
    // Order
    Route::prefix('order')->name('order.')->group(function() {
        Route::post('create', [CheckoutOrderController::class, 'create'])->name('create');
        Route::get('get/{orderId}', [CheckoutOrderController::class, 'get'])->name('get');
        Route::post('capture/{orderId}', [CheckoutOrderController::class, 'capture'])->name('capture');
    });

    // Capture
    Route::get('capture/get/{captureId}', [CaptureController::class, 'get'])->name('capture.get');
});

// PayPal Vault API
Route::prefix('paypal/vault')->name('paypal.vault.payment-token')->group(function() {
    // Payment token
    Route::prefix('payment-token')->name('payment-token.')->group(function() {
        Route::get('getAll/{customerId}', [PaymentTokenController::class, 'getAll'])->name('getAll');
        Route::post('create', [PaymentTokenController::class, 'create'])->name('create');
        Route::delete('delete/{paymentToken}', [PaymentTokenController::class, 'delete'])->name('delete');
    });

    // Order
    Route::prefix('order')->name('order.')->group(function() {
        Route::post('create', [VaultOrderController::class, 'create'])->name('create');
    });
});
