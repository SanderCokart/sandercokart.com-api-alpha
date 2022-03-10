<?php

/* Account */

use App\Http\Controllers\Auth\{AuthController,
    ChangeEmailController,
    ChangePasswordController,
    LogoutController,
    VerifyEmailController
};
use App\Http\Controllers\Models\{ArticleController,
    ArticleTypeController,
    FileController,
    RoleController,
    UserController
};

Route::get('/user', [AuthController::class, 'user']);
Route::post('/logout', LogoutController::class);

Route::group(['prefix' => 'account'], function () {
    Route::group(['prefix' => 'email'], function () {
        Route::patch('/change/{user}', ChangeEmailController::class);
        Route::post('/verify/{id}/{hash}', VerifyEmailController::class)->name('verification.verify')->middleware('signed:relative');
    });

    Route::group(['prefix' => 'password'], function () {
        Route::patch('/change', ChangePasswordController::class);
    });

});

/* Resources */
/* Article */
Route::post('/articles', [ArticleController::class, 'store']);
Route::patch('/articles/{article}', [ArticleController::class, 'update']);
Route::put('/articles/{article}', [ArticleController::class, 'update']);
Route::delete('/articles/{article}', [ArticleController::class, 'destroy']);

Route::apiResources(([
    'files' => FileController::class,
    'users' => UserController::class,
    'articleTypes' => ArticleTypeController::class,
    'roles' => RoleController::class,
]));
