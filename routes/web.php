<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Models\EducationProgram;

Route::get('/', function () {
    return view('portal');
});

Route::get('/sanat-atolye', function () {
    return view('sanat-atolye');
});

Route::redirect('/kis-okulu', '/sanat-atolye');

Route::get('/basvuru', [ApplicationController::class, 'create']);
Route::post('/basvuru', [ApplicationController::class, 'store']);
Route::get('/sessions/{program}', [ApplicationController::class, 'getSessions']);
Route::get('/tc-check/{tc}', [ApplicationController::class, 'checkTc'])->where('tc', '[0-9]{11}');

Route::get('/program/{id}', function ($id) {
    $program = EducationProgram::withCount('applications')->findOrFail($id);

    return response()->json([
        'id' => $program->id,
        'title' => $program->title,
        'age_range' => $program->age_range,
        'age_label' => $program->ageRequirementLabel(),
        'is_custom_schedule' => $program->is_custom_schedule,
        'registered' => $program->applications_count,
        'capacity' => $program->capacity,
        'is_full' => $program->applications_count >= $program->capacity,
        'is_open' => $program->is_open,
    ]);
});
