<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'age_range',
        'capacity',
        'is_open',
    ];

     public function applications()
    {
        return $this->hasMany(Application::class);
    }
    // Dinamik olarak “dolu mu” kontrol etmek istersen:
     public function getIsFullAttribute(): bool
    {
        return $this->applications()->count() >= $this->capacity;
    }
}
