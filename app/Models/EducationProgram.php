<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'instructor',
        'age_range',
        'location',
        'capacity',
        'is_open',
        'is_custom_schedule',
    ];

    protected $casts = [
        'is_open' => 'boolean',
        'is_custom_schedule' => 'boolean',
    ];

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function sessions()
    {
        return $this->hasMany(EducationSession::class);
    }

    public function getIsFullAttribute(): bool
    {
        return $this->applications()->count() >= $this->capacity;
    }

    public function syncOpenStatus(): void
    {
        $isFull = $this->applications()->count() >= $this->capacity;

        if ($isFull && $this->is_open) {
            $this->update(['is_open' => false]);
        }
    }

    /**
     * Yaş aralığı formatları: "4", "9-10", "16+", "15-24"
     */
    public function acceptsAge(int $age): bool
    {
        $range = trim((string) $this->age_range);

        if ($range === '') {
            return true;
        }

        if (preg_match('/^(\d+)\s*\+$/u', $range, $matches)) {
            return $age >= (int) $matches[1];
        }

        if (preg_match('/^(\d+)\s*[-–]\s*(\d+)$/u', $range, $matches)) {
            return $age >= (int) $matches[1] && $age <= (int) $matches[2];
        }

        if (preg_match('/^(\d+)$/u', $range, $matches)) {
            return $age === (int) $matches[1];
        }

        return true;
    }

    public function ageRequirementLabel(): string
    {
        $range = trim((string) $this->age_range);

        if (preg_match('/^(\d+)\s*\+$/u', $range, $matches)) {
            return $matches[1] . ' yaş ve üzeri';
        }

        if (preg_match('/^(\d+)\s*[-–]\s*(\d+)$/u', $range, $matches)) {
            return $matches[1] . '–' . $matches[2] . ' yaş';
        }

        if (preg_match('/^(\d+)$/u', $range, $matches)) {
            return $matches[1] . ' yaş';
        }

        return $range . ' yaş';
    }

    public static function ageFromBirthDate(string $birthDate): int
    {
        return Carbon::parse($birthDate)->age;
    }
}
