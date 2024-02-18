<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MintController;
use App\Http\Controllers\RedeemController;
use App\Http\Controllers\DashboardController;



//Route::middleware('auth')->group(function () {

    Route::get('/', function () {
        return view('index_JP');
    });

    Route::get('/en', function () {
        return view('index_EN');
    });

//});

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

//redeem success
Route::get('/redeem/success', function () {
    if (!session('form_submitted')) {
        // If the form was not submitted, redirect to a different page
        return redirect('/');
    }

    // Clear the session variable
    session()->forget('form_submitted');

    return view('redeem_success');
})->name('redeem.success');

//Auth stuff

Route::post('/redeem/{redeemId}/mark-as-redeemed', [RedeemController::class, 'markAsRedeemed'])->name('redeem.markAsRedeemed');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
