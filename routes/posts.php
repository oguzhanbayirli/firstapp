<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| Post Routes
|--------------------------------------------------------------------------
|
| Routes for creating, reading, updating, and deleting posts
|
*/

Route::middleware('mustBeLoggedIn')->group(function () {
    // Post CRUD routes
    Route::get('/create-post', [PostController::class, 'showCreateForm'])->name('posts.create');
    Route::post('/create-post', [PostController::class, 'storeNewPost'])->name('posts.store');
    Route::get('/post/{post}/edit', [PostController::class, 'showEditForm'])->name('posts.edit');
    Route::put('/post/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/post/{post}', [PostController::class, 'delete'])->name('posts.delete');
    
    // Search route
    Route::get('/search/{query}', [PostController::class, 'search'])->name('search');
});

// Public post view
Route::get('/post/{post}', [PostController::class, 'showSinglePost'])->name('post.show');
