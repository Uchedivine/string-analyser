<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StringController;

Route::post('/strings', [StringController::class, 'store']);
Route::get('/strings', [StringController::class, 'index']);
Route::get('/strings/filter-by-natural-language', [StringController::class, 'naturalFilter']);
Route::get('/strings/{value}', [StringController::class, 'show']);
Route::delete('/strings/{value}', [StringController::class, 'destroy']);
