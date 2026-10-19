<?php

use App\Http\Controllers\Api\VoyageController;
use Illuminate\Support\Facades\Route;

Route::apiResource('voyages', VoyageController::class)->names('api.voyages');
