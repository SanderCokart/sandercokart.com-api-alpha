<?php

use App\Models\ArticleType;
use App\Http\Controllers\Auth\{LoginController, RegisterController, ResetEmailController, ResetPasswordController};
use App\Http\Controllers\Models\{ArticleController};

//set json header
Route::post('/register', RegisterController::class)->name('register');
Route::post('/login', LoginController::class)->name('login');

/* PASSWORD */
Route::group(['prefix' => 'password', 'controller' => ResetPasswordController::class], function () {
    Route::post('/request', 'requestPassword')->middleware('throttle:3:60');
    Route::patch('/reset', 'passwordReset')->name('password.reset');
    Route::patch('/compromised/{user}/{token}', 'passwordCompromised')->name('password.compromised');
});

/* EMAIL */
Route::group(['prefix' => 'email'], function () {
    Route::patch('/compromised/{user}/{token}', ResetEmailController::class)->name('email.compromised');
});

/* ARTICLES */
Route::get('/articles/{articleType:name}/recent', [ArticleController::class, 'recent']);
Route::get('/articles/{articleType:name}/slugs', [ArticleController::class, 'slugs']);
Route::get('/articles/{articleType:name}', [ArticleController::class, 'index']);
Route::get('/articles/{articleType:name}/{article:slug}', [ArticleController::class, 'show']);


/* RESOURCES */
Route::apiResources([

]);


Route::get('/test/{id}', function ($id) {
//    $test = ArticleType::all();
//    $found = $test->where('id', $id)->firstOrFail();


    return ArticleType::where('id',$id)->firstOrFail();
});
