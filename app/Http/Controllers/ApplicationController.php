<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\EducationProgram;
use App\Models\EducationSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ApplicationController extends Controller
{
    public function create()
    {
        $programs = EducationProgram::where('is_open', true)
            ->withCount('applications')
            ->orderBy('title')
            ->get();

        return view('application.create', compact('programs'));
    }

    public function store(Request $request)
    {
        $program = EducationProgram::findOrFail($request->education_program_id);

        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'tc_no'      => 'required|digits:11',
            'birth_date' => 'required|date',
            'phone'      => 'required|string|max:20',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
            'education_program_id' => 'required|exists:education_programs,id',
            'signature' => 'required|string',
        ];

        if (!$program->is_custom_schedule) {
            $rules['session_id'] = 'required|exists:education_sessions,id';
        }

        $data = $request->validate($rules);

        try {
            DB::transaction(function () use ($request, $program, $data) {
                $program = EducationProgram::lockForUpdate()->findOrFail($program->id);

                $tcApplicationCount = Application::where('tc_no', $request->tc_no)->count();
                if ($tcApplicationCount >= 2) {
                    throw ValidationException::withMessages([
                        'tc_no' => 'Aynı TC kimlik numarası ile en fazla 2 kursa başvurulabilir.',
                    ]);
                }

                if (Application::where('tc_no', $request->tc_no)
                    ->where('education_program_id', $program->id)
                    ->exists()) {
                    throw ValidationException::withMessages([
                        'education_program_id' => 'Bu TC kimlik numarası ile seçilen kursa zaten başvuru yapılmıştır.',
                    ]);
                }

                $registered = Application::where('education_program_id', $program->id)->count();
                if ($registered >= $program->capacity) {
                    throw ValidationException::withMessages([
                        'education_program_id' => 'Seçtiğiniz kurs için kontenjan dolmuştur. Lütfen başka bir kurs seçiniz.',
                    ]);
                }

                if (!$program->is_custom_schedule && $request->session_id) {
                    $session = EducationSession::where('id', $request->session_id)
                        ->where('education_program_id', $program->id)
                        ->firstOrFail();

                    $sessionRegistered = Application::where('session_id', $session->id)->count();
                    if ($sessionRegistered >= $session->quota) {
                        throw ValidationException::withMessages([
                            'session_id' => 'Seçtiğiniz saat aralığı için kontenjan dolmuştur. Lütfen başka bir saat seçiniz.',
                        ]);
                    }
                }

                $application = new Application($data);
                $application->session_id = $request->session_id ?? null;
                $application->save();
            });
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect('/basvuru')->with('success', 'Başvurunuz başarıyla alınmıştır.');
    }

    public function getSessions($educationProgramId)
    {
        $program = EducationProgram::withCount('applications')->findOrFail($educationProgramId);

        if ($program->is_custom_schedule) {
            return response()->json([
                [
                    'id' => null,
                    'time_range' => 'Bu eğitime ait saat ve tarihler müdürlüğümüzce belirlenecektir.',
                    'quota' => null,
                    'registered' => null,
                    'is_full' => false,
                ]
            ]);
        }

        $programRegistered = $program->applications_count;
        $programCapacity = (int) $program->capacity;
        $programFull = $programRegistered >= $programCapacity;

        $sessions = $program->sessions()
            ->orderBy('start_time')
            ->get()
            ->map(function ($session) use ($programRegistered, $programCapacity, $programFull) {
                return [
                    'id' => $session->id,
                    'time_range' => "{$session->day} | " . substr($session->start_time, 0, 5) . " - " . substr($session->end_time, 0, 5),
                    'quota' => $programCapacity,
                    'registered' => $programRegistered,
                    'is_full' => $programFull,
                ];
            });

        return response()->json($sessions);
    }
}
