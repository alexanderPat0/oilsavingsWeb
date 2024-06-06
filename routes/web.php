<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('admins', [AdminController::class, 'index'])->name('admin.index');
Route::get('admins/create', [AdminController::class, 'create'])->name('admin.create');
Route::get('admins/{admin}/edit', [AdminController::class, 'edit'])->name('admin.edit');
Route::put('admins/{admin}', [AdminController::class, 'update'])->name('admin.update');
Route::delete('admins/{admin}', [AdminController::class, 'destroy'])->name('admin.destroy');
Route::get('actions', [AdminController::class, 'showActions'])->name('admin.actions');
Route::post('/admin/register', [AdminController::class, 'store'])->name('admin.store');

Route::get('users', [UserController::class, 'indexUsers'])->name('users.index');
Route::get('users/create', [UserController::class, 'create'])->name('users.create');
Route::post('users', [UserController::class, 'store'])->name('users.store');
Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

// Llamar a esta URL en caso de que se elimine el admin principal.
// Route::get('createSuperAdmin', [AdminController::class, 'createSuperAdmin']);
// Route::get('createSmolAdmin', [AdminController::class, 'createSmolAdmin']);

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['aut-6h'])
    ->name('profile');




require __DIR__.'/auth.php';
