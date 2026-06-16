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
                    'title' => 'Keman Sınıfı',
                    'instructor' => 'Samet Kök',
                    'age_range' => '10-18',
                    'location' => 'Kültür Sanat Evi',
                    'capacity' => 15,
                    'sessions' => [
                        ['day' => 'Pazartesi', 'start_time' => '13:30', 'end_time' => '14:30', 'quota' => 15],
                    ],
                ],
                [
                    'title' => 'Keman Sınıfı',
                    'instructor' => 'Samet Kök',
                    'age_range' => '18-50',
                    'location' => 'Kültür Sanat Evi',
                    'capacity' => 15,
                    'sessions' => [
                        ['day' => 'Pazar', 'start_time' => '19:00', 'end_time' => '20:30', 'quota' => 15],
                    ],
                ],
                [
                    'title' => 'Ritim Sınıfı',
                    'instructor' => 'Volkan Usul',
                    'age_range' => '10-18',
                    'location' => 'Kültür Sanat Evi',
                    'capacity' => 15,
                    'sessions' => [
                        ['day' => 'Salı', 'start_time' => '13:30', 'end_time' => '14:30', 'quota' => 15],
                    ],
                ],
                [
                    'title' => 'Darbuka Sınıfı',
                    'instructor' => 'Özgür Çakırlar',
                    'age_range' => '18-50',
                    'location' => 'Kültür Sanat Evi',
                    'capacity' => 20,
                    'sessions' => [
                        ['day' => 'Perşembe', 'start_time' => '17:00', 'end_time' => '18:00', 'quota' => 20],
                    ],
                ],
                [
                    'title' => 'Bateri Sınıfı',
                    'instructor' => 'Doğay Bağış',
                    'age_range' => '12-18',
                    'location' => 'Kültür Sanat Evi',
                    'capacity' => 6,
                    'sessions' => [
                        ['day' => 'Cuma', 'start_time' => '13:30', 'end_time' => '14:30', 'quota' => 6],
                    ],
                ],
                [
                    'title' => 'Klarnet Sınıfı',
                    'instructor' => 'Süreyya Meriç',
                    'age_range' => '14-50',
                    'location' => 'Kültür Sanat Evi',
                    'capacity' => 5,
                    'sessions' => [
                        ['day' => 'Cumartesi', 'start_time' => '12:00', 'end_time' => '13:30', 'quota' => 5],
                    ],
                ],
                [
                    'title' => 'Klarnet Sınıfı',
                    'instructor' => 'Süreyya Meriç',
                    'age_range' => '14-50',
                    'location' => 'Kültür Sanat Evi',
                    'capacity' => 5,
                    'sessions' => [
                        ['day' => 'Pazar', 'start_time' => '12:00', 'end_time' => '13:00', 'quota' => 5],
                    ],
                ],
                [
                    'title' => 'El Sanatları Sınıfı',
                    'instructor' => 'Berna Konyar Tenekeci',
                    'age_range' => '9-15',
                    'location' => 'Atatürk Kültür Merkezi',
                    'capacity' => 10,
                    'sessions' => [
                        ['day' => 'Cumartesi', 'start_time' => '17:00', 'end_time' => '18:00', 'quota' => 10],
                    ],
                ],
                [
                    'title' => 'Bale Sınıfı (5 Yaş)',
                    'instructor' => 'Gizem KURTİŞOĞLU',
                    'age_range' => '5',
                    'location' => 'Atatürk Kültür Merkezi',
                    'capacity' => 15,
                    'sessions' => [
                        ['day' => 'Pazartesi', 'start_time' => '11:00', 'end_time' => '11:40', 'quota' => 15],
                    ],
                ],
                [
                    'title' => 'Bale Sınıfı (6 Yaş)',
                    'instructor' => 'Gizem KURTİŞOĞLU',
                    'age_range' => '6',
                    'location' => 'Atatürk Kültür Merkezi',
                    'capacity' => 15,
                    'sessions' => [
                        ['day' => 'Pazartesi', 'start_time' => '11:45', 'end_time' => '12:25', 'quota' => 15],
                    ],
                ],
                [
                    'title' => 'Halk Oyunları',
                    'instructor' => 'Nebiye Şahiner',
                    'age_range' => '7-8',
                    'location' => 'Zübeyde Hanım Kadın Spor Merkezi',
                    'capacity' => 25,
                    'sessions' => [
                        ['day' => 'Pazartesi', 'start_time' => '15:00', 'end_time' => '16:00', 'quota' => 25],
                        ['day' => 'Cuma', 'start_time' => '15:00', 'end_time' => '16:00', 'quota' => 25],
                    ],
                ],
                [
                    'title' => 'Halk Oyunları',
                    'instructor' => 'Aydın Elbasan',
                    'age_range' => '9-10',
                    'location' => 'Kültür Sanat Evi',
                    'capacity' => 40,
                    'sessions' => [
                        ['day' => 'Pazartesi', 'start_time' => '16:00', 'end_time' => '17:00', 'quota' => 40],
                        ['day' => 'Çarşamba', 'start_time' => '16:00', 'end_time' => '17:00', 'quota' => 40],
                    ],
                ],
                [
                    'title' => 'Halk Oyunları',
                    'instructor' => 'Aydın Elbasan',
                    'age_range' => '18-50',
                    'location' => 'Kültür Sanat Evi',
                    'capacity' => 40,
                    'sessions' => [
                        ['day' => 'Pazartesi', 'start_time' => '18:00', 'end_time' => '19:15', 'quota' => 40],
                        ['day' => 'Perşembe', 'start_time' => '18:00', 'end_time' => '19:15', 'quota' => 40],
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
