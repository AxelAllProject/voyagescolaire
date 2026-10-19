<?php

use App\Http\Controllers\VoyageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes Web — Application Voyages Scolaires
|--------------------------------------------------------------------------
|
| Ces routes bénéficient du groupe de middlewares "web" (sessions, jeton
| CSRF, partage automatique des erreurs de validation $errors, conservation
| des anciennes saisies old() et messages flash en session).
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Liste et consultation des voyages
Route::get('/voyages', [VoyageController::class, 'index'])->name('voyages.index');

// Affichage du formulaire de création d'un voyage scolaire (Bootstrap 5)
Route::get('/voyages/create', [VoyageController::class, 'create'])->name('voyages.create');

// Traitement de la soumission du formulaire (validation, persistance et message flash)
Route::post('/voyages', [VoyageController::class, 'store'])->name('voyages.store');

// Opérations sur un voyage spécifique (consultation, mise à jour, suppression)
Route::get('/voyages/{voyage}', [VoyageController::class, 'show'])->name('voyages.show');
Route::put('/voyages/{voyage}', [VoyageController::class, 'update'])->name('voyages.update');
Route::patch('/voyages/{voyage}', [VoyageController::class, 'update'])->name('voyages.patch');
Route::delete('/voyages/{voyage}', [VoyageController::class, 'destroy'])->name('voyages.destroy');
