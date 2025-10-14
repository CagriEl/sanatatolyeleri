<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\EducationProgram;
use App\Models\EducationSession;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    /**
     * Başvuru formunu göster
     */
    public function create()
    {
        $programs = EducationProgram::where('is_open', true)->get();
        return view('application.create', compact('programs'));
    }

    /**
     * Başvuru kaydını oluştur
     */
    public function store(Request $request)
    {
        // Seçilen eğitimi bul
        $program = EducationProgram::findOrFail($request->education_program_id);

        // --- Validation Kuralları ---
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'tc_no'      => 'required|digits:11|unique:applications,tc_no',
            'birth_date' => 'required|date',
            'phone'      => 'required|string|max:20',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
            'education_program_id' => 'required|exists:education_programs,id',
            'signature' => 'required|string',
        ];

        // Eğer kurs "müdürlük tarafından belirlenecek" değilse -> saat zorunlu
        if (!$program->is_custom_schedule) {
            $rules['session_id'] = 'required|exists:education_sessions,id';
        }

        $data = $request->validate($rules);

        // --- KONTENJAN KONTROLÜ ---
        if (!$program->is_custom_schedule && isset($request->session_id)) {
            $session = EducationSession::find($request->session_id);
            if ($session) {
                $registered = Application::where('session_id', $session->id)->count();

                if ($registered >= $session->quota) {
                    return back()->withErrors([
                        'session_id' => 'Seçtiğiniz saat aralığı için kontenjan dolmuştur. Lütfen başka bir saat seçiniz.'
                    ])->withInput();
                }
            }
        }

        // --- Başvuru Oluştur ---
        $application = new Application($data);
        if (isset($request->session_id)) {
            $application->session_id = $request->session_id;
        } else {
            $application->session_id = null;
        }
        $application->save();

        return redirect('/basvuru')->with('success', 'Başvurunuz başarıyla alınmıştır.');
    }

    /**
     * Belirli bir eğitim programına ait saat aralıklarını getir
     */
    public function getSessions($educationProgramId)
    {
        $program = EducationProgram::findOrFail($educationProgramId);

        // Eğer kurs müdürlük tarafından planlanacaksa, özel mesaj dön
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

        // Normal kurslar için session listesi
        $sessions = DB::table('education_sessions')
            ->where('education_program_id', $educationProgramId)
            ->select('id', 'day', 'start_time', 'end_time', 'quota')
            ->orderBy('start_time', 'asc')
            ->get()
            ->map(function ($s) {
                $registered = Application::where('session_id', $s->id)->count();
                $quota = (int) $s->quota;
                $is_full = $registered >= $quota;

                return [
                    'id' => $s->id,
                    'time_range' => "{$s->day} | " . substr($s->start_time, 0, 5) . " - " . substr($s->end_time, 0, 5),
                    'quota' => $quota,
                    'registered' => $registered,
                    'is_full' => $is_full,
                ];
            });

        return response()->json($sessions);
    }
}
