<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
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

/*NO AUTH REQUIRED*/
Route::group([], function () {
    Route::post('/register', [UserController::class, 'create']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/check', [AuthController::class, 'check']);

    Route::group(['prefix' => 'password'], function () {
        Route::post('/request', [PasswordController::class, 'requestPassword'])->middleware('throttle:3:60');
        Route::patch('/reset', [PasswordController::class, 'passwordReset'])->name('password.reset');
        Route::patch('/compromised/{user}/{token}', [PasswordController::class, 'passwordCompromised'])->name('password.compromised');
    });

    Route::group(['prefix' => 'email'], function () {
        Route::patch('/compromised/{user}/{token}', [EmailController::class, 'emailCompromised'])->name('email.compromised');
    });

    Route::apiResources(([
        'posts' => PostController::class,
    ]));
});
/*NO AUTH REQUIRED*/

/*AUTH REQUIRED*/
Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'account'], function () {
    Route::group(['prefix' => 'email'], function () {
        Route::patch('/change/{user}', [EmailController::class, 'changeEmail']);
//        Route::get('/change/{id}/{hash}', [EmailController::class, 'change_email'])->name('email.change.undo');
        Route::get('/verify/{id}/{hash}', [EmailController::class, 'verify'])->name('verification.verify')->middleware('signed:relative');
    });

    Route::group(['prefix' => 'password'], function () {
        Route::patch('/change', [PasswordController::class, 'changePassword']);
    });

    Route::post('/logout', [AuthController::class, 'logout']);
});
/*AUTH REQUIRED*/

/*VERIFIED ONLY*/
Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
});
/*VERIFIED ONLY*/
