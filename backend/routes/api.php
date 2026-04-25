<?php

use App\Http\Controllers\API\BuildingController;
use App\Http\Controllers\API\TaskStatisticsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Management\AnalyticsController;
use App\Http\Controllers\Management\AnnouncementController;
use App\Http\Controllers\Management\BroadcastController;
use App\Http\Controllers\Management\ConversationController;
use App\Http\Controllers\Management\MembersController;
use App\Http\Controllers\Management\MessageController;
use App\Http\Controllers\Management\MessageInboxController;
use App\Http\Controllers\Management\ProfileController;
use App\Http\Controllers\Management\SettingsController;
use App\Http\Controllers\Management\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/register', RegisterController::class);
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::post('/refresh', [LoginController::class, 'refresh']);
    Route::get('/me', [LoginController::class, 'me']);
});

Route::prefix('management')->middleware(['auth:api', 'osbb'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::get('/settings', [SettingsController::class, 'index']);
    Route::put('/settings', [SettingsController::class, 'update']);
    Route::get('/announcements', [AnnouncementController::class, 'index']);
    Route::post('/announcements', [AnnouncementController::class, 'store']);
    Route::put('/announcements/{announcement}', [AnnouncementController::class, 'update']);
    Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy']);
    Route::get('/conversations', [ConversationController::class, 'index']);
    Route::post('/conversations', [ConversationController::class, 'store']);
    Route::get('/conversations/{conversation}/messages', [MessageController::class, 'index']);
    Route::post('/conversations/{conversation}/messages', [MessageController::class, 'store']);
    Route::get('/messages', [MessageInboxController::class, 'index']);
    Route::get('/messages/{message}/conversation', [MessageInboxController::class, 'conversation']);
    Route::post('/messages/{message}/reply', [MessageInboxController::class, 'reply']);
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/broadcasts', [BroadcastController::class, 'store']);
    Route::get('/members', [MembersController::class, 'index']);
    Route::get('/members/{user}', [MembersController::class, 'show']);
    Route::get('/analytics', [AnalyticsController::class, 'index']);
    Route::get('/buildings', [BuildingController::class, 'index']);
    Route::get('/buildings/{buildingId}/entrances', [BuildingController::class, 'entrances']);
    Route::get('/entrances/{entranceId}/apartments', [BuildingController::class, 'apartments']);
    Route::get('/tasks/statistics', [TaskStatisticsController::class, 'index']);
});
