<?php

use App\Http\Controllers\FollowController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [UserController::class, 'showCorrectHomePage'])->name('home');
Route::get('/post/{post}', [PostController::class, 'showSinglePost'])->name('post.show');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::post('/register', [UserController::class, 'register'])->name('register');
    Route::post('/login', [UserController::class, 'login'])->name('login');
});

// Authenticated user routes
Route::middleware('mustBeLoggedIn')->group(function () {
    // Post routes - keep old URLs for compatibility
    Route::get('/create-post', [PostController::class, 'showCreateForm'])->name('posts.create');
    Route::post('/create-post', [PostController::class, 'storeNewPost'])->name('posts.store');
    Route::get('/post/{post}/edit', [PostController::class, 'showEditForm'])->name('posts.edit');
    Route::put('/post/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/post/{post}', [PostController::class, 'delete'])->name('posts.delete');

    // User routes
    Route::prefix('profile')->as('profile.')->group(function () {
        Route::get('/{profile:username}', [UserController::class, 'profile'])->name('show');
        Route::get('/{profile:username}/followers', [UserController::class, 'profileFollowers'])->name('followers');
        Route::get('/{profile:username}/following', [UserController::class, 'profileFollowing'])->name('following');
    });

    Route::get('/manage-avatar', [UserController::class, 'showAvatarForm'])->name('avatar.edit');
    Route::post('/manage-avatar', [UserController::class, 'storeAvatar'])->name('avatar.store');

    // Follow routes
    Route::post('/follow/{user:username}', [FollowController::class, 'createFollow'])->name('follow');
    Route::post('/unfollow/{user:username}', [FollowController::class, 'removeFollow'])->name('unfollow');

    // Search route
    Route::get('/search/{query}', [PostController::class, 'search'])->name('search');

    // Logout
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
});

// Admin routes
Route::get('/admins-only', function () {
    return 'Only admins can see this page.';
})->middleware('can:visitAdminPages')->name('admin');

