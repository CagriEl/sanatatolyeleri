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

public function educationProgram()
{
    return $this->belongsTo(EducationProgram::class, 'education_program_id');
}

public function session()
{
    return $this->belongsTo(\App\Models\EducationSession::class, 'session_id');
        return $this->belongsTo(\App\Models\EducationSession::class, 'session_id');


}
}
