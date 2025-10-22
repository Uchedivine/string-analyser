<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StringController;

Route::prefix('strings')->group(function () {
    Route::post('/', [StringController::class, 'store']); 
    Route::get('/', [StringController::class, 'index']);
    Route::get('/filter-by-natural-language', [StringController::class, 'naturalFilter']);
    Route::get('/{value}', [StringController::class, 'show']);
    Route::delete('/{value}', [StringController::class, 'destroy']);
});
