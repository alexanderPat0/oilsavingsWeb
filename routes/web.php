<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/email/verify', [VerificationController::class, 'verify'])->name('intermediate.verify');

//Aqui solo van las rutas que ejerce el gerente/manager/superadmin, lo que sea
Route::get('admins', [AdminController::class, 'index'])->name('admin.index');
Route::get('admins/{admin}/edit', [AdminController::class, 'edit'])->name('admin.edit');
Route::put('admins/{admin}', [AdminController::class, 'update'])->name('admin.update');
Route::delete('admins/{admin}', [AdminController::class, 'destroy'])->name('admin.destroy');
Route::get('actions', [AdminController::class, 'showActions'])->name('admin.actions');
Route::post('/manager/activations/send/{id}', [AdminController::class, 'sendActivation'])->name('admin.activations.send');
Route::get('/manager/activations', [AdminController::class, 'pendingActivation'])->name('manager.activations');


Route::post('/admin/register', [AdminController::class, 'store'])->name('admin.store');

Route::get('users', [UserController::class, 'indexUsers'])->name('users.index');
Route::get('users/create', [UserController::class, 'create'])->name('users.create');
Route::post('users', [UserController::class, 'store'])->name('users.store');
Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

// Llamar a esta URL en caso de que se elimine el admin principal.
// Route::get('createSuperAdmin', [AdminController::class, 'createSuperAdmin']);
// Llamar a esta URL en caso de que se eliminen los admins pequeños.
// Route::get('createSmolAdmin', [AdminController::class, 'createSmolAdmin']);

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['aut-6h'])
    ->name('profile');




require __DIR__.'/auth.php';
