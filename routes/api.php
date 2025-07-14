<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\EstadisticaController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\BusquedaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/docentes', [DocenteController::class, 'index']);
Route::get('/docentes/{dni}', [DocenteController::class, 'detalle']);
Route::get('/docentes-filtros', [DocenteController::class, 'filtros']);
Route::get('/cursos', [CursoController::class, 'index']);
Route::get('/cursos/{origen}/{id}/participantes', [CursoController::class, 'getParticipantes']);
Route::get('/cursos-filtros', [CursoController::class, 'getFiltros']);
Route::get('/estadisticas', [EstadisticaController::class, 'index']);
Route::get('/inicio/resumen', [InicioController::class, 'resumen']);

// Ruta para búsqueda de certificados
Route::post('/iniciar-busqueda', [BusquedaController::class, 'iniciarBusqueda']);
Route::get('/progreso-busqueda', [BusquedaController::class, 'progresoBusqueda']);