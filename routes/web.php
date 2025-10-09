<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Models\EducationProgram;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/basvuru', [ApplicationController::class, 'create']);
Route::post('/basvuru', [ApplicationController::class, 'store']);
Route::get('/sessions/{educationProgram}', function (EducationProgram $educationProgram) {
    return $educationProgram->sessions->map(function ($session) {
        $isFull = $session->registered >= $session->quota;

        return [
            'id' => $session->id,
            'time_range' => $session->start_time . ' - ' . $session->end_time,
            'quota' => $session->quota,
            'registered' => $session->registered,
            'available' => max($session->quota - $session->registered, 0),
            'is_full' => $isFull,
        ];
    });
});