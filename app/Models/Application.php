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
    'signature', // 🟢 bu satır olmalı
    'is_approved',
    'email',
];

 public function educationProgram()
    {
        return $this->belongsTo(EducationProgram::class);
    }


}
