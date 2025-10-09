<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'education_program_id',
        'start_time',
        'end_time',
        'quota',
        'registered',
    ];

    protected $appends = ['time_range'];

    public function educationProgram()
    {
        return $this->belongsTo(EducationProgram::class);
    }

 public function getTimeRangeAttribute()
{
    // Eğer start_time ve end_time varsa
    if (!empty($this->start_time) && !empty($this->end_time)) {
        return "{$this->start_time} - {$this->end_time}";
    }
    return $this->time_range ?? null;
}



    public function isFull(): bool
    {
        return $this->registered >= $this->quota;
    }
}