<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeminiController;
use App\Http\Controllers\SemanticController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tes-ai', [GeminiController::class, 'testKoneksi']);
Route::get('/api/cari-jurnal', [SemanticController::class, 'cariJurnal']);