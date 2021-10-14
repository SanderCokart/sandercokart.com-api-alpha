<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\PasswordController;
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

//account without auth
Route::group(['prefix' => 'account'], function () {
    Route::post('/register', [AuthController::class, 'register']);//register user
    Route::post('/login', [AuthController::class, 'login']);//login user
    Route::get('/check', [AuthController::class, 'check']);//check if user is logged in

    Route::group(['prefix' => 'password'], function () {
        Route::post('/request', [PasswordController::class, 'requestPassword'])->middleware('throttle:3:60');//forgot password request
        Route::patch('/reset', [PasswordController::class, 'passwordReset'])->name('password.reset');//submit forgot password reset
        Route::patch('/compromised/{user}/{token}', [PasswordController::class, 'passwordCompromised'])->name('password.compromised');//submit forgot password reset
    });
});

//account with auth
Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'account'], function () {
    Route::group(['prefix' => 'email'], function () {
//        Route::patch('/change', [EmailController::class, 'email_change']);//change email
//        Route::get('/change/{id}/{hash}', [EmailController::class, 'change_email'])->name('email.change.undo');//change the password
        Route::get('/verify/{id}/{hash}', [EmailController::class, 'verify'])->name('verification.verify')->middleware('signed:relative');
    });

    Route::group(['prefix' => 'password'], function () {
        Route::patch('/change', [PasswordController::class, 'changePassword']);//change the password
    });

    Route::post('/logout', [AuthController::class, 'logout']);
});

//verified routes
Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {

});
