<?php

use App\Http\Controllers\ClasseController;
use App\Http\Controllers\PieceAdministrativeController;
use App\Http\Controllers\VoyageController;
use App\Models\Classe;
use App\Models\PieceAdministrative;
use App\Models\Voyage;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('accueil', [
        // withCount : la carte d'un voyage affiche le nombre de classes (voir x-voyage-card).
        'voyages' => Voyage::withCount('classes')->whereDate('date_debut', '>=', today())->orderBy('date_debut')->limit(3)->get(),
        'stats' => [
            'voyages' => Voyage::whereDate('date_fin', '>=', today())->count(),
            'places' => (int) Voyage::whereDate('date_fin', '>=', today())->sum('nb_places'),
            'classes' => Classe::count(),
            'pieces' => PieceAdministrative::count(),
        ],
    ]);
})->name('accueil');

// Réservé au personnel connecté ; les droits fins (rôles) sont vérifiés par les policies.
// Déclaré avant les routes publiques pour que /voyages/create ne soit pas pris pour un voyage.
Route::middleware('auth')->group(function () {
    Route::resource('voyages', VoyageController::class)->except(['index', 'show']);

    Route::resource('classes', ClasseController::class)
        ->except(['create', 'show'])
        ->parameters(['classes' => 'classe']);

    Route::resource('pieces-administratives', PieceAdministrativeController::class)
        ->except(['create', 'show'])
        ->parameters(['pieces-administratives' => 'piece']);

    Route::view('/mon-compte', 'compte')->name('compte');
});

// Catalogue public.
Route::resource('voyages', VoyageController::class)->only(['index', 'show']);
