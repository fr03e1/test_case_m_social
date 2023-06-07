<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('/auth/register', 'register');
    });

    Route::controller(UserController::class)->group(function ()  {
        Route::get('users/profile', 'show');
        Route::patch('users/profile', 'update');
        Route::delete('users/profile', 'delete');
    });


});
