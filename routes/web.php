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
    $program = EducationProgram::withCount('applications')->findOrFail($id);

    return response()->json([
        'id' => $program->id,
        'title' => $program->title,
        'is_custom_schedule' => $program->is_custom_schedule,
        'registered' => $program->applications_count,
        'capacity' => $program->capacity,
        'is_full' => $program->applications_count >= $program->capacity,
    ]);
});