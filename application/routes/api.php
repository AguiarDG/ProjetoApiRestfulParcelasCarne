<?php

use App\Http\Controllers\CarneController;
use Illuminate\Support\Facades\Route;

/**
 * CRUD CarneController
 * @class CarneController
 */
Route::get('/carnes', [CarneController::class, 'index']);
Route::get('/carnes/{carne}', [CarneController::class, 'show']);
Route::post('/carnes', [CarneController::class, 'store']);
