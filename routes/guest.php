<?php

use App\Http\Controllers\Auth\{EmailController, LoginController, PasswordController, RegisterController};
use App\Http\Controllers\Models\{ArticleController};

Route::group(['prefix' => 'account'], function () {
    Route::post('/register', RegisterController::class);
    Route::post('/login', LoginController::class);

    Route::group(['prefix' => 'password', 'controller' => PasswordController::class], function () {
        Route::post('/forgot', 'passwordForgot')->middleware('throttle:3:60');
        Route::patch('/reset', 'passwordReset')->middleware('throttle:1:60');
        Route::patch('/compromised', 'passwordCompromised');
    });

    Route::group(['prefix' => 'email', 'controller' => EmailController::class], function () {
        Route::patch('/compromised', 'emailCompromised');
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
