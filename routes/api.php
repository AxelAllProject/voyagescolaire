<?php

use App\Http\Controllers\VoyageController;
use Illuminate\Support\Facades\Route;

Route::apiResource('voyages', VoyageController::class);
