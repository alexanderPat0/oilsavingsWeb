<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
use App\Http\Middleware\EnsureIsSuperAdmin;
use Illuminate\Support\Facades\Route;

//Pantalla de inicio
Route::get('/', function () {
    return view('welcome');
})->name('home');


//RUTAS DE AUTENTICACIÓN 
Route::get('/email/verify', [VerificationController::class, 'verify'])->name('verify');
Route::post('/admin/register', [AdminController::class, 'store'])->name('admin.store');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login');

//Aqui solo van las rutas que ejerce el gerente/manager/superadmin, lo que sea
Route::middleware([EnsureIsSuperAdmin::class])->group(function () {
    Route::get('/admins', [AdminController::class, 'index'])->name('manager.admin-list');
    Route::get('/admins/{admin}/edit', [AdminController::class, 'edit'])->name('manager.admin-edit');
    Route::put('/admins/{admin}', [AdminController::class, 'update'])->name('manager.admin-update');  
    Route::delete('/admins/{admin}', [AdminController::class, 'destroy'])->name('manager.admin-destroy'); 
    Route::get('/actions', [AdminController::class, 'showActions'])->name('admin.actions');
    Route::post('/manager/activations/send/{id}', [AdminController::class, 'sendActivation'])->name('manager.activations.send');
    Route::get('/manager/activations', [AdminController::class, 'pendingActivation'])->name('manager.activations');
});

//Lista de usuarios.
Route::get('users', [UserController::class, 'index'])->name('admins.user-list');
//Edición de usuarios.
Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('admins.user-edit');
Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
//Eliminar usuario.
Route::delete('users/{user}', [UserController::class, 'destroy'])->name('admins.user-destroy');

// Llamar a esta URL en caso de que se elimine el admin principal.
// Route::get('createSuperAdmin', [AdminController::class, 'createSuperAdmin']);
// Llamar a esta URL en caso de que se eliminen los admins pequeños.
// Route::get('createSmolAdmin', [AdminController::class, 'createSmolAdmin']);

require __DIR__ . '/auth.php';
