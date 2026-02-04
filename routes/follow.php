<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FollowController;

/*
|--------------------------------------------------------------------------
| Follow Routes
|--------------------------------------------------------------------------
|
| Routes for following and unfollowing users
|
*/

Route::middleware('mustBeLoggedIn')->group(function () {
    Route::post('/follow/{user:username}', [FollowController::class, 'createFollow'])->name('follow');
    Route::post('/unfollow/{user:username}', [FollowController::class, 'removeFollow'])->name('unfollow');
});
