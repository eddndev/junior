<?php

use App\Http\Controllers\ProfileController;
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

// UI Components Showcase (solo en desarrollo)
if (config('app.debug')) {
    Route::get('/ui-components', function () {
        return view('ui-components.index');
    })->middleware(['auth'])->name('ui-components');
}

require __DIR__.'/socialite.php';
require __DIR__.'/auth.php';
