<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//account
Route::group(['prefix' => 'account'], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/check', [AuthController::class, 'check']);
    Route::group(['prefix' => 'password'], function () {
        Route::post('/request', [AuthController::class, 'request_password'])->middleware('throttle:3:60');
        Route::patch('/reset', [AuthController::class, 'password_reset'])->name('password.reset');
    });
});

//account with auth
Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'account'], function () {
    Route::group(['prefix' => 'email'], function () {
        Route::post('/request', [AuthController::class, 'request_email'])->middleware('throttle:3:60');
        Route::post('/change', [AuthController::class, 'email_change'])->name('email.change');
        Route::get('/verify/{id}/{hash}', [AuthController::class, 'verify'])->name('verification.verify')->middleware('signed');
    });
    Route::post('/logout', [AuthController::class, 'logout']);
});

//verified routes
Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {

});
