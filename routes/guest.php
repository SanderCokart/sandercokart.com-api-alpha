<?php

use App\Http\Controllers\Auth\{EmailController, LoginController, PasswordController, RegisterController};
use App\Http\Controllers\Models\{ArticleBannerController, ArticleController};

Route::group(['prefix' => 'account'], function () {
    Route::post('/register', RegisterController::class)->name('account.register');
    Route::post('/login', LoginController::class)->name('account.login');

    Route::group(['prefix' => 'password', 'controller' => PasswordController::class, 'middleware' => 'guest'], function () {
        Route::post('/forgot', 'passwordForgot')->middleware('throttle:credentials')->name('password.forgot');
        Route::patch('/reset', 'passwordReset')->middleware('throttle:credentials')->name('password.reset');
        Route::patch('/compromised', 'passwordCompromised')->middleware('throttle:credentials')->name('password.compromised');
    });

    Route::group(['prefix' => 'email', 'controller' => EmailController::class, 'middleware' => 'guest'], function () {
        Route::patch('/compromised', 'emailCompromised')->middleware('throttle:credentials')->name('email.compromised');
    });
});


/* ARTICLE */
Route::group(['prefix' => 'articles', 'controller' => ArticleController::class], function () {
    Route::get('/{articleType:name}/recent', 'recent')->name('articles.recent');
    Route::get('/{articleType:name}/slugs', 'slugs')->name('articles.slugs');
    Route::get('/{articleType:name}', 'index')->name('articles.index');
    Route::get('/{articleType:name}/{article:slug}', 'show')->name('articles.show');
});

/* RESOURCES */
Route::apiResources([

]);
