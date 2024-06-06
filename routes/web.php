<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;

// web.php
Route::view('/', 'welcome');
Route::get('users', [UserController::class, 'indexUsers'])->name('users.index');
Route::get('admins', [UserController::class, 'indexAdmins'])->name('admins.index');
Route::get('users/create', [UserController::class, 'create'])->name('users.create');
Route::post('users', [UserController::class, 'store'])->name('users.store');
Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
Route::get('/verify-email/{id}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::get('actions', [AdminController::class, 'showActions'])->name('manager.actions');


// Llamar a esta URL en caso de que se elimine el admin principal.
// Route::get('createSuperAdmin', [AdminController::class, 'createSuperAdmin']);




Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');




require __DIR__.'/auth.php';
