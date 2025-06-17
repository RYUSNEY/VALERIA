<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocenteController;


Route::get('/docentes', [DocenteController::class, 'index']);
Route::get('/docentes/{dni}', [DocenteController::class, 'detalle']);
Route::get('/docentes-filtros', [DocenteController::class, 'filtros']);
