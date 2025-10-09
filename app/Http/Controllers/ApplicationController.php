<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\EducationProgram;
use App\Models\EducationSession;

class ApplicationController extends Controller
{
    /**
     * Başvuru formunu göster.
     */
    public function create()
    {
        $programs = EducationProgram::where('is_open', true)->get();
        return view('application.create', compact('programs'));
    }

    /**
     * Başvuru kaydını veritabanına ekle.
     */
    public function store(Request $request)
    {
        // 🔹 Form doğrulama
        $data = $request->validate([
            'first_name'            => 'required|string|max:255',
            'last_name'             => 'required|string|max:255',
            'email'                 => 'required|email|max:255',
            'tc_no'                 => 'required|digits:11|unique:applications,tc_no',
            'birth_date'            => 'required|date',
            'phone'                 => 'required|string|max:20',
            'parent_name'           => 'required|string|max:255',
            'parent_phone'          => 'required|string|max:20',
            'education_program_id'  => 'required|exists:education_programs,id',
            'session_id'            => 'required|exists:education_sessions,id',
            'signature'             => 'required|string',
        ]);

        // 🔹 Seçilen eğitim programını al
        $program = EducationProgram::findOrFail($data['education_program_id']);

        // 🔹 Seçilen saat aralığını al
        $session = EducationSession::findOrFail($data['session_id']);

        // 🔹 Kontenjan kontrolü (saat bazlı)
        $currentCount = Application::where('session_id', $session->id)->count();

        if ($currentCount >= $session->quota) {
            return back()->withErrors([
                'session_id' => 'Seçtiğiniz saat aralığının kontenjanı dolmuştur. Lütfen başka bir saat seçiniz.',
            ])->withInput();
        }

        // 🔹 Kontenjan kontrolü (program bazlı)
        $programCount = Application::where('education_program_id', $program->id)->count();
        if ($programCount >= $program->capacity) {
            return back()->withErrors([
                'education_program_id' => 'Bu eğitimin genel kontenjanı dolmuştur. Lütfen başka bir program seçiniz.',
            ])->withInput();
        }

        // 🔹 Başvuru kaydı oluştur
        Application::create($data);

        return redirect('/basvuru')->with('success', 'Başvurunuz başarıyla alınmıştır.');
    }
}
