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
        Route::patch('/change', [EmailController::class, 'emailChange']);
        Route::post('/verify', [EmailController::class, 'emailVerify']);
        Route::post('/verify/retry', [EmailController::class, 'emailVerifyResend']);
    });

    Route::group(['prefix' => 'password'], function () {
        Route::patch('/change', [PasswordController::class, 'passwordChange']);
    });

    Route::get('/user', AuthController::class);
    Route::post('/logout', LogoutController::class);
});

/* Resources */
/* Article */
Route::post('/articles', [ArticleController::class, 'store']);
Route::patch('/articles/{article}', [ArticleController::class, 'update']);
Route::put('/articles/{article}', [ArticleController::class, 'update']);
Route::delete('/articles/{article}', [ArticleController::class, 'destroy']);

Route::apiResources(([
    'files'        => FileController::class,
    'users'        => UserController::class,
    'articleTypes' => ArticleTypeController::class,
    'roles'        => RoleController::class,
]));
