<?php

use App\Http\Controllers\FollowController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Models\Post;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [UserController::class, 'showCorrectHomePage'])->name('login');
Route::get('/post/{post}', [PostController::class, 'showSinglePost']);

// Guest only routes
Route::middleware('guest')->group(function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
});

// Authenticated routes
Route::middleware('mustBeLoggedIn')->group(function () {
    Route::get('/create-post', [PostController::class, 'showCreateForm']);
    Route::post('/create-post', [PostController::class, 'storeNewPost']);
    Route::post('/logout', [UserController::class, 'logout']);

    Route::get('/profile/{profile:username}', [UserController::class, 'profile']);
    Route::get('/profile/{profile:username}/followers', [UserController::class, 'profileFollowers']);
    Route::get('/profile/{profile:username}/following', [UserController::class, 'profileFollowing']);

    Route::get('/manage-avatar', [UserController::class, 'showAvatarForm']);
    Route::post('/manage-avatar', [UserController::class, 'storeAvatar']);

    Route::post('/create-follow/{user:username}', [FollowController::class, 'createFollow']);
    Route::post('/remove-follow/{user:username}', [FollowController::class, 'removeFollow']);

    Route::get('/search{query}', [PostController::class, 'search']);
});

// Post authorization routes
Route::get('/post/{post}/edit', [PostController::class, 'showEditForm'])->middleware('can:update,post');
Route::put('/post/{post}', [PostController::class, 'update'])->middleware('can:update,post');
Route::delete('/post/{post}', [PostController::class, 'delete'])->middleware('can:delete,post');

// Admin routes
Route::get('/admins-only', function () {
    return 'Only admins can see this page.';
})->middleware('can:visitAdminPages');

