<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeminiController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tes-ai', [GeminiController::class, 'testKoneksi']);
Route::get('/login', function () {
    return view('login');
});