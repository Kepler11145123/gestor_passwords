<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EstadisticasApiController;

/*
|--------------------------------------------------------------------------
| API Routes - Rutas API propias para consumo externo
|--------------------------------------------------------------------------
*/

Route::middleware(['web', 'auth', 'verified'])->prefix('v1')->group(function () {
    Route::get('/estadisticas', [EstadisticasApiController::class, 'estadisticas']);
    Route::get('/bovedas',      [EstadisticasApiController::class, 'bovedas']);
});
