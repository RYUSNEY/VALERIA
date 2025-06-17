<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// Ruta de inicio
Route::get('/', [DashboardController::class, 'index'])->name('inicio');

// Rutas para secciones
Route::get('/docentes', [DashboardController::class, 'docentes'])->name('docentes');
Route::get('/cursos', function () {
    return view('cursos'); 
})->name('cursos');
Route::get('/topicos', [DashboardController::class, 'topicos'])->name('topicos');
Route::get('/otros', function () {
    return view('otros');
})->name('otros');
Route::view('/docentes', 'docentes');
