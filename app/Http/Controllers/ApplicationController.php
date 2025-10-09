<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\EducationProgram;
use App\Models\EducationSession;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    // 📌 Başvuru formunu göster
    public function create()
    {
        $programs = EducationProgram::where('is_open', true)->get();
        return view('application.create', compact('programs'));
    }

    // 📌 Başvuruyu kaydet
    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'tc_no' => 'required|digits:11|unique:applications,tc_no',
            'birth_date' => 'required|date',
            'phone' => 'required|string|max:20',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
            'education_program_id' => 'required|exists:education_programs,id',
            'session_id' => 'required|exists:education_sessions,id',
            'signature' => 'required|string',
        ]);

        $session = EducationSession::find($data['session_id']);

        // 🔒 Kontenjan kontrolü
        $currentCount = Application::where('session_id', $session->id)->count();
        if ($currentCount >= $session->quota) {
            return back()->withErrors([
                'session_id' => 'Bu saat aralığının kontenjanı dolmuştur. Lütfen başka bir saat seçiniz.'
            ])->withInput();
        }

        // 📥 Başvuru kaydı oluştur
        $application = Application::create($data);

        // 🔄 Session kontenjanını güncelle
        $session->current_count = Application::where('session_id', $session->id)->count();
        $session->save();

        return redirect('/basvuru')->with('success', 'Başvurunuz başarıyla alınmıştır.');
    }

    // 📊 Saat aralıklarını JSON olarak döndür (ön form için)
   public function getSessions($educationProgramId)
{
    $sessions = DB::table('education_sessions')
        ->where('education_program_id', $educationProgramId)
        ->select('id', 'day', 'start_time', 'end_time', 'quota') // 🟢 day alanı eklendi
        ->orderBy('start_time', 'asc')
        ->get()
        ->map(function ($s) {
            $registered = \App\Models\Application::where('session_id', $s->id)->count();
            $quota = (int) $s->quota;
            $is_full = $registered >= $quota;

            return [
                'id' => $s->id,
                'day' => $s->day ?? 'Gün Belirtilmemiş', // 🟢 Formda görünsün
                'time_range' => substr($s->start_time, 0, 5) . ' - ' . substr($s->end_time, 0, 5),
                'quota' => $quota,
                'registered' => $registered,
                'is_full' => $is_full,
            ];
        });

    return response()->json($sessions);
}

}
