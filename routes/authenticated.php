<?php

/* Account */

use App\Http\Controllers\Auth\{AuthController, EmailController, LogoutController, PasswordController};
use App\Http\Controllers\Models\{ArticleController,
    ArticleTypeController,
    FileController,
    RoleController,
    UserController
};

Route::group(['prefix' => 'account'], function () {
    Route::group(['prefix' => 'email'], function () {
        Route::patch('/change', [EmailController::class, 'emailChange'])
             ->middleware('throttle:1,60')
             ->name('email.change');
        Route::post('/verify', [EmailController::class, 'emailVerify'])
             ->middleware('throttle:1,60')
             ->name('email.verify');
        Route::post('/verify/retry', [EmailController::class, 'emailVerifyResend'])
             ->middleware('throttle:3,10')
             ->name('email.verify.retry');
    });

    Route::group(['prefix' => 'password'], function () {
        Route::patch('/change', [PasswordController::class, 'passwordChange'])
             ->middleware('throttle:1,60')
             ->name('password.change');
    });

    Route::get('/user', AuthController::class)->name('account.user');
    Route::post('/logout', LogoutController::class)->name('account.logout');
});

/* Resources */
/* Article */
Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
Route::patch('/articles/{article}', [ArticleController::class, 'update'])->name('articles.update');
Route::delete('/articles/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');

Route::apiResources([
    'files'        => FileController::class,
    'users'        => UserController::class,
    'articleTypes' => ArticleTypeController::class,
    'roles'        => RoleController::class,
]);
