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
    public const MAX_APPLICATIONS_PER_TC = 2;

    public function create()
    {
        $programs = EducationProgram::query()
            ->where('is_open', true)
            ->withCount('applications')
            ->get()
            ->filter(fn (EducationProgram $program) => $program->applications_count < $program->capacity)
            ->sortBy(function (EducationProgram $program) {
                if (preg_match('/^(\d+)/', (string) $program->age_range, $matches)) {
                    return (int) $matches[1];
                }

                return 999;
            })
            ->values();

        return view('application.create', compact('programs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'education_program_id' => 'required|exists:education_programs,id',
        ]);

        $program = EducationProgram::with('sessions')->findOrFail($request->education_program_id);

        if (! $program->is_open || $program->is_full) {
            throw ValidationException::withMessages([
                'education_program_id' => 'Seçtiğiniz kurs başvuruya kapalı veya kontenjanı dolmuştur.',
            ]);
        }

        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'tc_no'      => 'required|digits:11',
            'birth_date' => 'required|date|before:today',
            'phone'      => 'required|string|max:20',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
            'education_program_id' => 'required|exists:education_programs,id',
            'signature' => 'required|string|min:100',
        ];

        $sessionCount = $program->sessions->count();

        if (! $program->is_custom_schedule && $sessionCount === 1) {
            $rules['session_id'] = 'required|exists:education_sessions,id';
        }

        $data = $request->validate($rules, [
            'signature.required' => 'Lütfen veli imzasını çizin.',
            'signature.min' => 'Lütfen veli imzasını çizin.',
            'session_id.required' => 'Lütfen saat aralığı seçin.',
            'birth_date.before' => 'Doğum tarihi bugünden önce olmalıdır.',
        ]);

        $age = EducationProgram::ageFromBirthDate($data['birth_date']);
        if (! $program->acceptsAge($age)) {
            throw ValidationException::withMessages([
                'birth_date' => "Bu program {$program->ageRequirementLabel()} içindir. Başvuranın yaşı: {$age}.",
            ]);
        }

        try {
            DB::transaction(function () use ($request, $program, $data, $sessionCount, $age) {
                $program = EducationProgram::with('sessions')->lockForUpdate()->findOrFail($program->id);

                if (! $program->is_open) {
                    throw ValidationException::withMessages([
                        'education_program_id' => 'Seçtiğiniz kurs başvuruya kapanmıştır.',
                    ]);
                }

                $tcApplicationCount = Application::where('tc_no', $request->tc_no)->count();
                if ($tcApplicationCount >= self::MAX_APPLICATIONS_PER_TC) {
                    throw ValidationException::withMessages([
                        'tc_no' => 'Aynı TC kimlik numarası ile en fazla ' . self::MAX_APPLICATIONS_PER_TC . ' kursa başvurulabilir.',
                    ]);
                }

                if (Application::where('tc_no', $request->tc_no)
                    ->where('education_program_id', $program->id)
                    ->exists()) {
                    throw ValidationException::withMessages([
                        'education_program_id' => 'Bu TC kimlik numarası ile seçilen kursa zaten başvuru yapılmıştır.',
                    ]);
                }

                if (! $program->acceptsAge($age)) {
                    throw ValidationException::withMessages([
                        'birth_date' => "Bu program {$program->ageRequirementLabel()} içindir. Başvuranın yaşı: {$age}.",
                    ]);
                }

                $registered = Application::where('education_program_id', $program->id)->count();
                if ($registered >= $program->capacity) {
                    $program->update(['is_open' => false]);
                    throw ValidationException::withMessages([
                        'education_program_id' => 'Seçtiğiniz kurs için kontenjan dolmuştur. Lütfen başka bir kurs seçiniz.',
                    ]);
                }

                if (! $program->is_custom_schedule && $request->session_id && $sessionCount === 1) {
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
                $application->session_id = $sessionCount > 1
                    ? null
                    : ($request->session_id ?? $program->sessions->first()?->id);
                $application->save();

                $program->syncOpenStatus();
            });
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect('/basvuru')->with('success', 'Başvurunuz başarıyla alınmıştır.');
    }

    public function checkTc(string $tcNo)
    {
        if (! preg_match('/^\d{11}$/', $tcNo)) {
            return response()->json([
                'valid' => false,
                'count' => 0,
                'remaining' => self::MAX_APPLICATIONS_PER_TC,
                'max' => self::MAX_APPLICATIONS_PER_TC,
                'message' => 'Geçerli bir TC kimlik numarası giriniz.',
            ]);
        }

        $count = Application::where('tc_no', $tcNo)->count();
        $remaining = max(0, self::MAX_APPLICATIONS_PER_TC - $count);

        return response()->json([
            'valid' => true,
            'count' => $count,
            'remaining' => $remaining,
            'max' => self::MAX_APPLICATIONS_PER_TC,
            'message' => $remaining === 0
                ? 'Bu TC ile başvuru hakkınız dolmuştur (2/2).'
                : "Bu TC ile {$count}/" . self::MAX_APPLICATIONS_PER_TC . " başvuru yapılmış. Kalan hak: {$remaining}.",
        ]);
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

        $sessions = $program->sessions()->orderBy('start_time')->get();

        if ($sessions->count() > 1) {
            $days = $sessions->pluck('day')->join(' & ');
            $first = $sessions->first();
            $timeRange = "{$days}: " . substr($first->start_time, 0, 5) . ' – ' . substr($first->end_time, 0, 5);

            return response()->json([[
                'id' => null,
                'time_range' => $timeRange,
                'quota' => $programCapacity,
                'registered' => $programRegistered,
                'is_full' => $programFull,
                'is_combined' => true,
            ]]);
        }

        $sessions = $sessions->map(function ($session) use ($programRegistered, $programCapacity, $programFull) {
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
