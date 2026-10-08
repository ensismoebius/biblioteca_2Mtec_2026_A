<?php

use App\Http\Controllers\Api\LivroController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('livros', LivroController::class)->only(['index', 'show']);
});
