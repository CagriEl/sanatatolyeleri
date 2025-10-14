<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Models\EducationProgram;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/basvuru', [ApplicationController::class, 'create']);
Route::post('/basvuru', [ApplicationController::class, 'store']);
Route::get('/sessions/{program}', [ApplicationController::class, 'getSessions']);


Route::get('/program/{id}', function ($id) {
    $program = EducationProgram::select('id', 'title', 'is_custom_schedule')->findOrFail($id);
    return response()->json($program);
});