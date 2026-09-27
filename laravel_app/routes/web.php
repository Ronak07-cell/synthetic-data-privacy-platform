<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SyntheticDataController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/generate', [SyntheticDataController::class, 'showForm']);
Route::post('/generate', [SyntheticDataController::class, 'generate']);
