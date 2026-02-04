<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
|
| Routes for viewing user profiles, followers, following, and avatar management
|
*/

Route::middleware('mustBeLoggedIn')->group(function () {
    // Profile viewing routes
    Route::prefix('profile')->as('profile.')->group(function () {
        Route::get('/{profile:username}', [UserController::class, 'profile'])->name('show');
        Route::get('/{profile:username}/followers', [UserController::class, 'profileFollowers'])->name('followers');
        Route::get('/{profile:username}/following', [UserController::class, 'profileFollowing'])->name('following');
    });

    // Avatar management routes
    Route::get('/manage-avatar', [UserController::class, 'showAvatarForm'])->name('avatar.edit');
    Route::post('/manage-avatar', [UserController::class, 'storeAvatar'])->name('avatar.store');
});
