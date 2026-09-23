<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\EducationProgram;
use App\Models\EducationSession;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EducationProgramSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            Application::query()->delete();
            EducationSession::query()->delete();
            EducationProgram::query()->delete();

            $programs = [
                [
                    'title' => 'Bale (4 Yaş — Sabah)',
                    'instructor' => 'Gizem KURTİŞOĞLU',
                    'age_range' => '4',
                    'location' => 'Atatürk Kültür Merkezi (AKM)',
                    'capacity' => 15,
                    'sessions' => [
                        ['day' => 'Pazartesi', 'start_time' => '11:00', 'end_time' => '11:40', 'quota' => 15],
                    ],
                ],
                [
                    'title' => 'Bale (5 Yaş — Sabah)',
                    'instructor' => 'Gizem KURTİŞOĞLU',
                    'age_range' => '5',
                    'location' => 'Atatürk Kültür Merkezi (AKM)',
                    'capacity' => 15,
                    'sessions' => [
                        ['day' => 'Pazartesi', 'start_time' => '11:45', 'end_time' => '12:25', 'quota' => 15],
                    ],
                ],
                [
                    'title' => 'Bale (4 Yaş — Öğlen)',
                    'instructor' => 'Gizem KURTİŞOĞLU',
                    'age_range' => '4',
                    'location' => 'Atatürk Kültür Merkezi (AKM)',
                    'capacity' => 15,
                    'sessions' => [
                        ['day' => 'Pazartesi', 'start_time' => '13:30', 'end_time' => '14:10', 'quota' => 15],
                    ],
                ],
                [
                    'title' => 'Bale (5 Yaş — Öğlen)',
                    'instructor' => 'Gizem KURTİŞOĞLU',
                    'age_range' => '5',
                    'location' => 'Atatürk Kültür Merkezi (AKM)',
                    'capacity' => 15,
                    'sessions' => [
                        ['day' => 'Pazartesi', 'start_time' => '14:20', 'end_time' => '15:00', 'quota' => 15],
                    ],
                ],
                [
                    'title' => 'Bale (6 Yaş — Öğlen)',
                    'instructor' => 'Gizem KURTİŞOĞLU',
                    'age_range' => '6',
                    'location' => 'Atatürk Kültür Merkezi (AKM)',
                    'capacity' => 15,
                    'sessions' => [
                        ['day' => 'Pazartesi', 'start_time' => '15:30', 'end_time' => '16:10', 'quota' => 15],
                    ],
                ],
                [
                    'title' => 'Halk Oyunları — Gizem KURTİŞOĞLU (9-10 Yaş)',
                    'instructor' => 'Gizem KURTİŞOĞLU',
                    'age_range' => '9-10',
                    'location' => 'Atatürk Kültür Merkezi (AKM)',
                    'capacity' => 20,
                    'sessions' => [
                        ['day' => 'Pazartesi', 'start_time' => '16:30', 'end_time' => '17:30', 'quota' => 20],
                    ],
                ],
                [
                    'title' => 'Klarnet Kursu (16+ Yaş)',
                    'instructor' => 'Koray Ihnalı',
                    'age_range' => '16+',
                    'location' => 'Atatürk Kültür Merkezi (AKM)',
                    'capacity' => 10,
                    'sessions' => [
                        ['day' => 'Pazartesi', 'start_time' => '18:15', 'end_time' => '19:15', 'quota' => 10],
                    ],
                ],
                [
                    'title' => 'Gençlik Korosu (15-24 Yaş)',
                    'instructor' => 'Samet Kök',
                    'age_range' => '15-24',
                    'location' => 'Atatürk Kültür Merkezi (AKM)',
                    'capacity' => 60,
                    'sessions' => [
                        ['day' => 'Perşembe', 'start_time' => '19:30', 'end_time' => '21:00', 'quota' => 60],
                    ],
                ],
                [
                    'title' => 'El Sanatları (9-15 Yaş)',
                    'instructor' => 'Berna Hoca',
                    'age_range' => '9-15',
                    'location' => 'Atatürk Kültür Merkezi (AKM)',
                    'capacity' => 5,
                    'sessions' => [
                        ['day' => 'Cumartesi', 'start_time' => '12:00', 'end_time' => '14:00', 'quota' => 5],
                    ],
                ],
                [
                    'title' => 'Halk Oyunları — Aydın Elbasan (14-55 Yaş)',
                    'instructor' => 'Aydın Elbasan',
                    'age_range' => '14-55',
                    'location' => 'Kültür Sanat Evi',
                    'capacity' => 60,
                    'sessions' => [
                        ['day' => 'Pazartesi', 'start_time' => '19:30', 'end_time' => '21:30', 'quota' => 60],
                    ],
                ],
                [
                    'title' => 'Drama (7-9 Yaş)',
                    'instructor' => 'Fatih Umutlu',
                    'age_range' => '7-9',
                    'location' => 'Kültür Sanat Evi',
                    'capacity' => 20,
                    'sessions' => [
                        ['day' => 'Cumartesi', 'start_time' => '15:00', 'end_time' => '16:00', 'quota' => 20],
                    ],
                ],
                [
                    'title' => 'Drama (10-14 Yaş)',
                    'instructor' => 'Fatih Umutlu',
                    'age_range' => '10-14',
                    'location' => 'Kültür Sanat Evi',
                    'capacity' => 20,
                    'sessions' => [
                        ['day' => 'Pazar', 'start_time' => '15:00', 'end_time' => '17:00', 'quota' => 20],
                    ],
                ],
                [
                    'title' => 'Yetişkin Tiyatro (15-55 Yaş)',
                    'instructor' => 'Fatih Umutlu',
                    'age_range' => '15-55',
                    'location' => 'Kültür Sanat Evi',
                    'capacity' => 20,
                    'sessions' => [
                        ['day' => 'Pazar', 'start_time' => '17:00', 'end_time' => '19:00', 'quota' => 20],
                    ],
                ],
                [
                    'title' => 'Yetişkin Halk Oyunları — Nebiye Şahiner (18-50 Yaş)',
                    'instructor' => 'Nebiye Şahiner',
                    'age_range' => '18-50',
                    'location' => 'Zübeyde Hanım Kadın Spor Merkezi',
                    'capacity' => 20,
                    'sessions' => [
                        ['day' => 'Salı', 'start_time' => '15:00', 'end_time' => '16:00', 'quota' => 20],
                        ['day' => 'Perşembe', 'start_time' => '15:00', 'end_time' => '16:00', 'quota' => 20],
                    ],
                ],
            ];

            foreach ($programs as $programData) {
                $sessions = $programData['sessions'];
                unset($programData['sessions']);

                $program = EducationProgram::create([
                    ...$programData,
                    'is_open' => true,
                    'is_custom_schedule' => false,
                ]);

                foreach ($sessions as $session) {
                    $program->sessions()->create($session);
                }
            }
        });
    }
}
