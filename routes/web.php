<?php

use App\Models\Voyage;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $voyages = Voyage::query()
        ->whereDate('date_debut', '>=', today())
        ->orderBy('date_debut')
        ->limit(3)
        ->get();

    return view('accueil', ['voyages' => $voyages]);
})->name('accueil');
