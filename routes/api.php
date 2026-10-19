<?php

use App\Http\Controllers\VoyageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes API — Ressource Voyage (/api/voyages)
|--------------------------------------------------------------------------
|
| Les noms de routes API sont préfixés par "api." afin d'éviter toute
| collision avec les routes Web définies dans routes/web.php.
|
*/

Route::get('/voyages', [VoyageController::class, 'index'])->name('api.voyages.index');
Route::get('/voyages/{voyage}', [VoyageController::class, 'show'])->name('api.voyages.show');
Route::post('/voyages', [VoyageController::class, 'store'])->name('api.voyages.store');
Route::put('/voyages/{voyage}', [VoyageController::class, 'update'])->name('api.voyages.update');
Route::patch('/voyages/{voyage}', [VoyageController::class, 'update'])->name('api.voyages.patch');
Route::delete('/voyages/{voyage}', [VoyageController::class, 'destroy'])->name('api.voyages.destroy');
