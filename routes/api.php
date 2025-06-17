<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\EstadisticaController;
use App\Http\Controllers\InicioController;

Route::get('/docentes', [DocenteController::class, 'index']);
Route::get('/docentes/{dni}', [DocenteController::class, 'detalle']);
Route::get('/docentes-filtros', [DocenteController::class, 'filtros']);
Route::get('/cursos', [CursoController::class, 'index']);
Route::get('/cursos/{origen}/{id}/participantes', [CursoController::class, 'getParticipantes']);
Route::get('/cursos-filtros', [CursoController::class, 'getFiltros']);
Route::get('/estadisticas', [EstadisticaController::class, 'index']);
Route::get('/inicio/resumen', [InicioController::class, 'resumen']);