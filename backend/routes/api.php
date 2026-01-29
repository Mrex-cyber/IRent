<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/register', RegisterController::class);
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::post('/refresh', [LoginController::class, 'refresh']);
    Route::get('/me', [LoginController::class, 'me']);
});
