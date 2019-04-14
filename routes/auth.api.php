<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'auth',
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');

    Route::group([
        'middleware' => 'auth:api',
    ], function () {
        Route::get('user', 'UsersController@get');
    });
});
