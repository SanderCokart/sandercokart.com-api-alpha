<?php

use App\Http\Controllers\Auth\{EmailController, LoginController, PasswordController, RegisterController};
use App\Http\Controllers\Models\{ArticleController};

Route::group(['prefix' => 'account'], function () {
    Route::post('/register', RegisterController::class)->name('account.register');
    Route::post('/login', LoginController::class)->name('account.login');

    Route::group(['prefix' => 'password', 'controller' => PasswordController::class], function () {
        Route::post('/forgot', 'passwordForgot')->middleware('throttle:3:60')->name('password.forgot');
        Route::patch('/reset', 'passwordReset')->middleware('throttle:1:60')->name('password.reset');
        Route::patch('/compromised', 'passwordCompromised')->middleware('throttle:1:60')->name('password.compromised');
    });

    Route::group(['prefix' => 'email', 'controller' => EmailController::class], function () {
        Route::patch('/compromised', 'emailCompromised')->middleware('throttle:1:60')->name('email.compromised');
    });
});


/* ARTICLE */
Route::get('/articles/{articleType:name}/recent', [ArticleController::class, 'recent'])->name('articles.recent');
Route::get('/articles/{articleType:name}/slugs', [ArticleController::class, 'slugs'])->name('articles.slugs');
Route::get('/articles/{articleType:name}', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{articleType:name}/{article:slug}', [ArticleController::class, 'show'])->name('articles.show');


/* RESOURCES */
Route::apiResources([

]);
