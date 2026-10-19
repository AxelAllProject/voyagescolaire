<?php

use App\Http\Controllers\VoyageController;
use Illuminate\Support\Facades\Route;

Route::get('/voyages', [VoyageController::class, 'index'])->name('voyages.index');
Route::get('/voyages/{voyage}', [VoyageController::class, 'show'])->name('voyages.show');
Route::post('/voyages', [VoyageController::class, 'store'])->name('voyages.store');
Route::put('/voyages/{voyage}', [VoyageController::class, 'update'])->name('voyages.update');
Route::patch('/voyages/{voyage}', [VoyageController::class, 'update'])->name('voyages.patch');
Route::delete('/voyages/{voyage}', [VoyageController::class, 'destroy'])->name('voyages.destroy');
