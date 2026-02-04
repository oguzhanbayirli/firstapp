<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;

/*
|--------------------------------------------------------------------------
| Chat Routes
|--------------------------------------------------------------------------
|
| Routes for real-time chat functionality
|
*/

Route::middleware('mustBeLoggedIn')->group(function () {
    Route::post('/chat', [ChatController::class, 'sendMessage'])->name('chat.send');
});
