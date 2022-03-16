<?php

use App\Http\Controllers\Auth\{LoginController, RegisterController, ResetEmailController, PasswordController};
use App\Http\Controllers\Models\{ArticleController};
use App\Models\ArticleType;

Route::group(['prefix' => 'account'], function () {
    Route::post('/register', RegisterController::class);
    Route::post('/login', LoginController::class);

    Route::group(['prefix' => 'password', 'controller' => PasswordController::class], function () {
        Route::post('/request', 'requestPassword')->middleware('throttle:3:60');
        Route::patch('/reset', 'passwordReset');
        Route::patch('/compromised', 'passwordCompromised');
    });

    Route::group(['prefix' => 'email'], function () {
        Route::patch('/compromised/{user}/{token}', ResetEmailController::class)->name('email.compromised');
    });
});



/* ARTICLE */
Route::get('/articles/{articleType:name}/recent', [ArticleController::class, 'recent']);
Route::get('/articles/{articleType:name}/slugs', [ArticleController::class, 'slugs']);
Route::get('/articles/{articleType:name}', [ArticleController::class, 'index']);
Route::get('/articles/{articleType:name}/{article:slug}', [ArticleController::class, 'show']);


/* RESOURCES */
Route::apiResources([

]);
