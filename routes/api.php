<?php

use App\Http\Controllers\Api\JetonController;
use App\Http\Controllers\Api\VoyageController;
use Illuminate\Support\Facades\Route;

// Obtenir un jeton : limité comme la connexion du site.
Route::post('jetons', [JetonController::class, 'store'])
    ->middleware('throttle:login')
    ->name('api.jetons.store');

// Lecture publique.
Route::apiResource('voyages', VoyageController::class)
    ->only(['index', 'show'])
    ->names('api.voyages');

// Écriture : jeton obligatoire (en-tête Authorization: Bearer …).
Route::middleware(['auth:sanctum', 'compte.valide'])->group(function () {
    Route::delete('jetons/actuel', [JetonController::class, 'destroy'])->name('api.jetons.destroy');

    Route::apiResource('voyages', VoyageController::class)
        ->except(['index', 'show'])
        ->names('api.voyages');
});
