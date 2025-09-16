<?php

use App\Http\Controllers\API\SubscriptionController;
use App\Http\Controllers\API\UserAuthController;
use App\Http\Controllers\API\PackageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('register', [UserAuthController::class, 'register'])->name('register');
Route::post('login', [UserAuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('get-user', [UserAuthController::class, 'userInfo'])->name('get-user');
    Route::post('logout', [UserAuthController::class, 'logOut'])->name('logout');

    Route::resource('packages', PackageController::class);


    Route::post('subscriptions/create', [SubscriptionController::class,"store"]);
    Route::post('subscriptions/update/{id}', [SubscriptionController::class,"update"]);
    Route::post('subscriptions/updatestatus/{id}', [SubscriptionController::class,"updatestatus"]);
    
    Route::get('subscriptions/getpandingsubscription', [SubscriptionController::class,"getpandingsubscription"]);
    
    Route::get('subscriptions/showsubscripedpackage', [SubscriptionController::class,"showsubscripedpackage"]);
});