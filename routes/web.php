<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// RRHH - User Management Routes
// TODO: Add middleware to check 'gestionar-usuarios' permission
Route::middleware(['auth'])->group(function () {
    // Resource routes for users (index, create, store, show, edit, update, destroy)
    Route::resource('users', UserController::class);

    // Additional route to restore soft-deleted users
    Route::post('users/{id}/restore', [UserController::class, 'restore'])
        ->name('users.restore');
});

// UI Components Showcase (solo en desarrollo)
if (config('app.debug')) {
    Route::get('/ui-components', function () {
        return view('ui-components.index');
    })->middleware(['auth'])->name('ui-components');
}

require __DIR__.'/socialite.php';
require __DIR__.'/auth.php';
