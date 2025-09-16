<?php

use App\Http\Controllers\Api\V1\RecipeController;
use Illuminate\Support\Facades\Route;

Route::apiResource('recipes', RecipeController::class)->only(['index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('recipes', RecipeController::class)->only(['store']);
});
