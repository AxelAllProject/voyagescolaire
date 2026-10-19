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
        'voyages' => Voyage::whereDate('date_debut', '>=', today())->orderBy('date_debut')->limit(3)->get(),
        'stats' => [
            'voyages' => Voyage::whereDate('date_fin', '>=', today())->count(),
            'places' => (int) Voyage::whereDate('date_fin', '>=', today())->sum('nb_places'),
            'classes' => Classe::count(),
            'pieces' => PieceAdministrative::count(),
        ],
    ]);
})->name('accueil');

Route::resource('voyages', VoyageController::class);

Route::resource('classes', ClasseController::class)
    ->except(['create', 'show'])
    ->parameters(['classes' => 'classe']);

Route::resource('pieces-administratives', PieceAdministrativeController::class)
    ->except(['create', 'show'])
    ->parameters(['pieces-administratives' => 'piece']);
