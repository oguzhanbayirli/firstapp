<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| Routes for user registration and login (guest users only)
|
*/

Route::middleware('guest')->group(function () {
    Route::post('/register', [UserController::class, 'register'])->name('register');
    Route::post('/login', [UserController::class, 'login'])->name('login');
});

Route::middleware('mustBeLoggedIn')->group(function () {
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
});
