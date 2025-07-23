<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\EducationProgram;

class ApplicationController extends Controller
{
    public function create()
    {
     $programs = EducationProgram::where('is_open', true)->get();
    return view('application.create', compact('programs'));
        
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'tc_no' => 'required|digits:11|unique:applications,tc_no',
            'birth_date' => 'required|date',
            'phone' => 'required|string|max:20',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
            'education_program_id' => 'required|exists:education_programs,id',
            'signature' => 'required|string',
        ]);

        $program = EducationProgram::find($data['education_program_id']);

        // KONTENJAN DOLULUK KONTROLÜ
        $currentCount = Application::where('education_program_id', $program->id)->count();

        if ($currentCount >= $program->capacity) {
            return back()->withErrors([
                'education_program_id' => 'Kontenjan dolmuştur. Lütfen başka bir program seçiniz.'
            ])->withInput();
        }

        // Başvuru oluştur
        Application::create($data);

        return redirect('/basvuru')->with('success', 'Başvurunuz başarıyla alınmıştır.');
    }
}
