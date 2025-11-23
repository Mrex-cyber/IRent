<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DatabaseTestController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/test-db', [DatabaseTestController::class, 'testConnection']);
