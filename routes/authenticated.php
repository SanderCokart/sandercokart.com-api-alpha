<?php

/* Account */

use App\Http\Controllers\Auth\{AuthController, EmailController, LogoutController, PasswordController};
use App\Http\Controllers\Models\{ArticleBannerController,
    ArticleController,
    ArticleTypeController,
    FileController,
    RoleController,
    UserController
};
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

Route::group(['prefix' => 'account'], function () {
    Route::group(['prefix' => 'email'], function () {
        Route::patch('/change', [EmailController::class, 'emailChange'])
            ->middleware('throttle:3,60')
            ->name('email.change');
        Route::post('/verify', [EmailController::class, 'emailVerify'])
            ->middleware('throttle:3,10')
            ->name('email.verify');
        Route::post('/verify/retry', [EmailController::class, 'emailVerifyRetry'])
            ->middleware('throttle:3,10')
            ->name('email.verify.retry');
    });

    Route::group(['prefix' => 'password'], function () {
        Route::patch('/change', [PasswordController::class, 'passwordChange'])
            ->middleware('throttle:3,60')
            ->name('password.change');
    });

    Route::get('/user', AuthController::class)
        ->name('account.user');
    Route::post('/logout', LogoutController::class)
        ->name('account.logout');
});

/* Resources */
Route::post('/articles/{articleType:name}', [ArticleController::class, 'store'])
    ->name('articles.store');
Route::patch('/articles/{articleType:name}/{article}', [ArticleController::class, 'update'])
    ->name('articles.update');
Route::delete('/articles/{articleType:name}/{article}', [ArticleController::class, 'destroy'])
    ->name('articles.destroy');

Route::apiResource('users', UserController::class)
    ->except(['update']);

Route::apiResources([
    'files'        => FileController::class,
    'articleTypes' => ArticleTypeController::class,
    'roles'        => RoleController::class,
]);

Route::post('/removeSession', function () {
    DB::table('sessions')->where('user_id', auth()->user()->id)->delete();
    return Response::HTTP_OK;
});
