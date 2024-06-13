<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
use App\Http\Middleware\EnsureIsSuperAdmin;
use App\Http\Middleware\FirebaseUser;
use Illuminate\Support\Facades\Route;


Route::get('/health', function () {
    return response()->json(['status' => 'OK'], 200);
});

//Pantalla de inicio
Route::get('/', function () {
    return view('welcome');
})->name('home');


//RUTAS DE AUTENTICACIÓN 
Route::get('/email/verify', [VerificationController::class, 'verify'])->name('verify');
Route::post('/admin/register', [AdminController::class, 'store'])->name('admin.store');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::any('/logout', [AdminController::class, 'logout'])->name('logout');


// Aplicar FirebaseUser Middleware a todas las rutas relevantes
// Aquí solo van las rutas que ejerce el gerente/manager/superadmin, lo que sea
Route::middleware([FirebaseUser::class, EnsureIsSuperAdmin::class])->group(function () {
    
    Route::get('/admins', [AdminController::class, 'index'])->name('manager.admin-list');
    Route::get('/admins/{admin}/edit', [AdminController::class, 'edit'])->name('manager.admin-edit');
    Route::put('/admins/{admin}', [AdminController::class, 'update'])->name('manager.admin-update');
    Route::delete('/admins/{admin}', [AdminController::class, 'destroy'])->name('manager.admin-destroy');
    Route::get('/actions', [AdminController::class, 'showActions'])->name('admin.actions');
    Route::post('/manager/activate/{id}', [AdminController::class, 'activate'])->name('manager.activate');
    Route::get('/manager/activations', [AdminController::class, 'pendingActivation'])->name('manager.activations');


    //Lista de usuarios.
    Route::get('users', [UserController::class, 'index'])->name('admins.user-list')->withoutMiddleware([EnsureIsSuperAdmin::class]);
    //Edición de usuarios.
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('admins.user-edit')->withoutMiddleware([EnsureIsSuperAdmin::class]);
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update')->withoutMiddleware([EnsureIsSuperAdmin::class]);
    //Eliminar usuario.
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('admins.user-destroy')->withoutMiddleware([EnsureIsSuperAdmin::class]);



    //Eliminar reviews.
    Route::delete('/review/{review}', [ReviewController::class, 'destroy'])->name('users.review-destroy')->withoutMiddleware([EnsureIsSuperAdmin::class]);

});

Route::get('/reviews', [ReviewController::class, 'index'])->name('users.review-list');
Route::post('/review/create', [ReviewController::class, 'store'])->name('users.review-create');


//Aqui las rutas de las reviews para que cualquier usuario, logueado o no, pueda verlas.

// Llamar a esta URL en caso de que se elimine el admin principal.
// Route::get('createSuperAdmin', [AdminController::class, 'createSuperAdmin']);
// Llamar a esta URL en caso de que se eliminen los admins pequeños.
// Route::get('createSmolAdmin', [AdminController::class, 'createSmolAdmin']);

require __DIR__ . '/auth.php';
