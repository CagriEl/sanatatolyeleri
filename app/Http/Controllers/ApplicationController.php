<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\EducationProgram;
use Carbon\Carbon;

class ApplicationController extends Controller
{
    /**
     * Başvuru formunu göster.
     */
    public function create()
    {
        // Her program için mevcut başvuru sayısını da çekiyoruz
        $programs = EducationProgram::withCount('applications')->get();

        return view('application.create', compact('programs'));
    }

    /**
     * Formdan gelen başvuruyu işleyip kaydet.
     */
    public function store(Request $request)
    {
        // Eğer mobil formdan "gg/aa/yyyy" formatında geldiyse, MySQL uyumlu hale getiriyoruz
        if ($request->filled('birth_date') && str_contains($request->birth_date, '/')) {
            try {
                $dt = Carbon::createFromFormat('d/m/Y', $request->birth_date);
                $request->merge([
                    'birth_date' => $dt->format('Y-m-d'),
                ]);
            } catch (\Exception $e) {
                // Hatalı parse durumunda eski değeri koruyabiliriz
            }
        }

        // Gelen veriyi doğrula
        $data = $request->validate([
            'first_name'           => 'required|string|max:255',
            'last_name'            => 'required|string|max:255',
            'email'                => 'required|email|max:255',
            'tc_no'                => 'required|digits:11|unique:applications,tc_no',
            'birth_date'           => 'required|date',
            'phone'                => 'required|string|max:50',
            'parent_name'          => 'required|string|max:255',
            'parent_phone'         => 'required|string|max:50',
            'education_program_id' => 'required|exists:education_programs,id',
            'signature'            => 'required',
        ]);

        // Kontenjan kontrolü
        $program = EducationProgram::find($data['education_program_id']);
        if (! $program->is_open || $program->applications()->count() >= $program->capacity) {
            return back()
                ->withErrors([
                    'education_program_id' => 'Kontenjan dolmuştur. Lütfen başka bir program seçiniz.',
                ])
                ->withInput();
        }

        // Kayıt oluştur
        Application::create($data);

        return redirect('/basvuru')
            ->with('success', 'Başvurunuz başarıyla alındı.');
    }
}
