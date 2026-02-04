<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/', [UserController::class, 'showCorrectHomePage'])->name('home');

// Admin routes
Route::get('/admins-only', function () {
    return 'Only admins can see this page.';
})->middleware('can:visitAdminPages')->name('admin');

// Load modular route files
require __DIR__.'/auth.php';
require __DIR__.'/posts.php';
require __DIR__.'/profile.php';
require __DIR__.'/follow.php';
require __DIR__.'/chat.php';

