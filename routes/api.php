<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ChangeEmailController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetEmailController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Models\FileController;
use App\Http\Controllers\Models\PostController;
use App\Http\Controllers\Models\RoleController;
use App\Http\Controllers\Models\UserController;
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
    Route::post('/register', RegisterController::class)->name('register');
    Route::post('/login', LoginController::class)->name('login');

    Route::group(['prefix' => 'password'], function () {
        Route::post('/request', [ResetPasswordController::class, 'requestPassword'])->middleware('throttle:3:60');
        Route::patch('/reset', [ResetPasswordController::class, 'passwordReset'])->name('password.reset');
        Route::patch('/compromised/{user}/{token}', [ResetPasswordController::class, 'passwordCompromised'])->name('password.compromised');
    });

    Route::group(['prefix' => 'email'], function () {
        Route::patch('/compromised/{user}/{token}', ResetEmailController::class)->name('email.compromised');
    });

    Route::apiResources(([
        'posts' => PostController::class,
        'roles' => RoleController::class
    ]));
});

/*NO AUTH REQUIRED*/

/*AUTH REQUIRED*/
Route::group(['middleware' => 'auth:sanctum'], function () {

    /*ACCOUNT RELATED*/
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', LogoutController::class);

    Route::group(['prefix' => 'account'], function () {
        Route::group(['prefix' => 'email'], function () {
            Route::patch('/change/{user}', [ChangeEmailController::class, 'changeEmail']);
            Route::post('/verify/{id}/{hash}', VerifyEmailController::class)->name('verification.verify')->middleware('signed:relative');
        });

        Route::group(['prefix' => 'password'], function () {
            Route::patch('/change', [ChangePasswordController::class, 'changePassword']);
        });

    });

    /*ACCOUNT RELATED*/

    /*RESOURCES WITH AUTH*/
    Route::apiResources(([
        'files' => FileController::class,
        'users' => UserController::class
    ]));
    /*RESOURCES WITH AUTH*/
});
/*AUTH REQUIRED*/

/*VERIFIED ONLY*/
Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
});
/*VERIFIED ONLY*/
