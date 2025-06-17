<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// Ruta de inicio
Route::get('/', [DashboardController::class, 'index'])->name('inicio');

// Rutas para secciones
Route::get('/docentes', [DashboardController::class, 'docentes'])->name('docentes');
Route::get('/cursos', [DashboardController::class, 'cursos'])->name('cursos');
Route::get('/otros', [DashboardController::class, 'cursos'])->name('otros');

Route::view('/docentes', 'docentes');
Route::view('/cursos', 'cursos');
Route::view('/otros', 'otros');
