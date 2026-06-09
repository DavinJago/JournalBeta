<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeminiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SemanticController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/tes-ai', [GeminiController::class, 'testKoneksi']);
Route::get('/api/cari-jurnal', [SemanticController::class, 'cariJurnal']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');