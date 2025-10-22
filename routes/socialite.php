<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\GithubAuthController;
use Illuminate\Support\Facades\Route;

// Google OAuth Routes
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
Route::delete('/auth/google/disconnect', [GoogleAuthController::class, 'disconnect'])->middleware('auth')->name('auth.google.disconnect');

// GitHub OAuth Routes
Route::get('/auth/github/redirect', [GithubAuthController::class, 'redirect'])->name('auth.github.redirect');
Route::get('/auth/github/callback', [GithubAuthController::class, 'callback'])->name('auth.github.callback');
Route::delete('/auth/github/disconnect', [GithubAuthController::class, 'disconnect'])->middleware('auth')->name('auth.github.disconnect');