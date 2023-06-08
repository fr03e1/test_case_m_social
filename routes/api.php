<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\MovieController;
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

    Route::controller(MovieController::class)->group(function ()  {
        Route::get('movies', 'index');
        Route::post('movies/{movie}', 'addToFavorite');
        Route::delete('movies/{movie}', 'deleteFromFavorite');
        Route::get('movies/new_for_you', 'getMoviesNotInFavorite');
    });
});
