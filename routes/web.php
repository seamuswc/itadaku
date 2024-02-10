<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MintController;
use App\Http\Controllers\RedeemController;

// Display the form (assuming a single page for both forms)
Route::get('/', function () {
    return view('index_JP');
});

Route::get('/en', function () {
    return view('index_EN');
});

// Handle form submissions
Route::post('/mint', [MintController::class, 'store']);
Route::post('/redeem', [RedeemController::class, 'store']);

//mint success
Route::get('/mint/success', function () {
    if (!session('form_submitted')) {
        // If the form was not submitted, redirect to a different page
        return redirect('/');
    }

    // Clear the session variable
    session()->forget('form_submitted');

    return view('mint_success');
})->name('mint.success');

//Auth stuff