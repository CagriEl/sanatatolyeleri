<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'tc_no',
        'birth_date',
        'phone',
        'parent_phone',
        'parent_name',
        'education_program_id',
        'signature',
        'is_approved',
        'session_id',
        'email',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_approved' => 'boolean',
    ];

    public function educationProgram()
    {
        return $this->belongsTo(EducationProgram::class, 'education_program_id');
    }

    public function session()
    {
        return $this->belongsTo(EducationSession::class, 'session_id');
    }

    protected static function booted()
    {
        static::created(function (Application $application) {
            $application->educationProgram?->syncOpenStatus();
        });

        static::deleted(function (Application $application) {
            if ($application->session_id) {
                $session = EducationSession::find($application->session_id);
                if ($session) {
                    $session->current_count = Application::where('session_id', $session->id)->count();
                    $session->save();
                }
            }

            $program = EducationProgram::find($application->education_program_id);
            if ($program && ! $program->is_full && ! $program->is_open) {
                $program->update(['is_open' => true]);
            }
        });
    }
}
