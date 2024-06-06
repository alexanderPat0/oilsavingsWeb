<?php

// web.php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('users', [UserController::class, 'indexUsers'])->name('users.index');
Route::get('admins', [UserController::class, 'indexAdmins'])->name('admins.index');
Route::get('users/create', [UserController::class, 'create'])->name('users.create');
Route::post('users', [UserController::class, 'store'])->name('users.store');
Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
