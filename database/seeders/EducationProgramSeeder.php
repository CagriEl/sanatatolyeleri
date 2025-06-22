<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EducationProgram;

class EducationProgramSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            ['title' => 'Yaratıcı Drama Atölyesi (5-6 Yaş)', 'age_range' => '5-6', 'capacity' => 20],
            ['title' => 'Yaratıcı Drama Atölyesi (7-9 Yaş)', 'age_range' => '7-9', 'capacity' => 20],
            ['title' => 'Yaratıcı Drama Atölyesi (10-14 Yaş)', 'age_range' => '10-14', 'capacity' => 20],
            ['title' => 'Tiyatro Atölyesi (15 Yaş ve Üzeri)', 'age_range' => '15+', 'capacity' => 20],
            ['title' => 'Çok Sesli Çocuk Koro Atölyesi (5-18 Yaş)', 'age_range' => '5-18', 'capacity' => 40],
            ['title' => 'Gitar Atölyesi (6-18 Yaş)', 'age_range' => '6-18', 'capacity' => 8],
            ['title' => 'El Sanatları Atölyesi (10-18 Yaş)', 'age_range' => '10-18', 'capacity' => 12],
            ['title' => 'El Sanatları Atölyesi (Kadın)', 'age_range' => 'Yetişkin Kadın', 'capacity' => 15],
            ['title' => 'Resim Atölyesi (Çocuk) (7-10 Yaş)', 'age_range' => '7-10', 'capacity' => 24],
            ['title' => 'Bale Atölyesi (5-8 Yaş)', 'age_range' => '5-8', 'capacity' => 20],
            ['title' => 'Klarnet Atölyesi (Çocuk) (12-18 Yaş)', 'age_range' => '12-18', 'capacity' => 4],
            ['title' => 'Ritim Atölyesi (Çocuk) (9-11 Yaş)', 'age_range' => '9-11', 'capacity' => 16],
            ['title' => 'Halk Oyunları Atölyesi (Çocuk) (9-10 Yaş)', 'age_range' => '9-10', 'capacity' => 16],
            ['title' => 'Halk Oyunları Atölyesi (Çocuk) (7-10 Yaş)', 'age_range' => '7-10', 'capacity' => 16],
        ];

        foreach ($programs as $program) {
            EducationProgram::create($program);
        }
    }
}
