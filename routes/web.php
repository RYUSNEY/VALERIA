<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// Ruta de inicio
Route::get('/', [DashboardController::class, 'index'])->name('inicio');

// Rutas para secciones
Route::get('/docentes', [DashboardController::class, 'docentes'])->name('docentes');
Route::get('/cursos', [DashboardController::class, 'cursos'])->name('cursos');
Route::get('/topicos', [DashboardController::class, 'topicos'])->name('topicos');
Route::get('/otros', [DashboardController::class, 'otros'])->name('otros');

Route::view('/docentes', 'docentes');
