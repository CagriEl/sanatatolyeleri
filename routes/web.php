<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/basvuru', [ApplicationController::class, 'create']);
Route::post('/basvuru', [ApplicationController::class, 'store']);
