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
}
