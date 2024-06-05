<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\FirebaseAuthController;

Route::post('/login', [FirebaseAuthController::class, 'login']);

Route::get('/', function () {
    return view('welcome');
});
