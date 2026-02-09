<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Inspiring;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\ChatController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', [UserController::class, 'showCorrectHomePage'])->name('home');
Route::get('/post/{post}', [PostController::class, 'showSinglePost'])->name('post.show');

// Livewire Test Route
Route::get('/test-livewire', function() {
    return view('test-livewire');
});

// Admin routes
Route::get('/admins-only', fn() => 'Only admins can see this page.')
    ->middleware('can:visitAdminPages')
    ->name('admin');

// Guest Routes (Auth)
Route::middleware('guest')->group(function () {
    Route::post('/register', [UserController::class, 'register'])->name('register');
    Route::post('/login', [UserController::class, 'login'])->name('login');
});

// Authenticated Routes
Route::middleware('mustBeLoggedIn')->group(function () {
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    
    // Posts
    Route::get('/create-post', [PostController::class, 'showCreateForm'])->name('posts.create');
    Route::post('/create-post', [PostController::class, 'storeNewPost'])->name('posts.store');
    Route::get('/post/{post}/edit', [PostController::class, 'showEditForm'])->name('posts.edit');
    Route::put('/post/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/post/{post}', [PostController::class, 'delete'])->name('posts.delete');
    Route::get('/search/{query}', [PostController::class, 'search'])->name('search');
    
    // Follow
    Route::post('/follow/{user:username}', [FollowController::class, 'createFollow'])->name('follow');
    Route::post('/unfollow/{user:username}', [FollowController::class, 'removeFollow'])->name('unfollow');
    
    // Chat
    Route::post('/chat', [ChatController::class, 'sendMessage'])->name('chat.send');
    
    // Profile
    Route::prefix('profile')->as('profile.')->group(function () {
        Route::get('/{profile:username}', [UserController::class, 'profile'])->name('show');
        Route::get('/{profile:username}/followers', [UserController::class, 'profileFollowers'])->name('followers');
        Route::get('/{profile:username}/following', [UserController::class, 'profileFollowing'])->name('following');
    });
    
    // Avatar
    Route::get('/manage-avatar', [UserController::class, 'showAvatarForm'])->name('avatar.edit');
    Route::post('/manage-avatar', [UserController::class, 'storeAvatar'])->name('avatar.store');
});

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
*/
Broadcast::channel('App.Models.User.{id}', fn($user, $id) => (int) $user->id === (int) $id);
Broadcast::channel('chat', fn($user) => $user ? ['username' => $user->username, 'avatar' => $user->avatar] : false);

/*
|--------------------------------------------------------------------------
| Console Commands
|--------------------------------------------------------------------------
*/
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

