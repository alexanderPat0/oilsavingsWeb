<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\FirebaseAuthController;

// Route::post('/login', [FirebaseAuthController::class, 'login']);
// Route::get('/login', function () { return view('login');});

Route::get('/login', function () { return view('login');})->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Route::get('/', function () {
//     return view('welcome');
// });

Route::resource('users','App\Http\Controllers\UserController');

Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');

Route::get('/users', [UserController::class, 'indexUsers'])->name('users.index');
Route::get('/admins', [UserController::class, 'indexAdmins'])->name('admins.index');


Route::get('/', function () {
    return redirect()->route('users.index');
});

