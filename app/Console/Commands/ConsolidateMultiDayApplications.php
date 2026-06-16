<?php

namespace App\Console\Commands;

use App\Models\Application;
use App\Models\EducationProgram;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ConsolidateMultiDayApplications extends Command
{
    protected $signature = 'applications:consolidate-multi-day
                            {--dry-run : Değişiklik yapmadan önizle}
                            {--instructor= : Sadece belirtilen eğitmenin programları (ör: "Aydın Elbasan")}
                            {--program-id= : Sadece belirtilen program ID}';

    protected $description = 'Çok günlü kurslardaki başvuruları eğitmen/program bazında birleştirir';

    private const HALK_OYUNLARI_TITLES = [
        ['instructor' => 'Nebiye Şahiner', 'age_range' => '7-8', 'title' => 'Halk Oyunları — Nebiye Şahiner (7-8 Yaş)'],
        ['instructor' => 'Aydın Elbasan', 'age_range' => '9-10', 'title' => 'Halk Oyunları — Aydın Elbasan (9-10 Yaş)'],
        ['instructor' => 'Aydın Elbasan', 'age_range' => '18-50', 'title' => 'Halk Oyunları — Aydın Elbasan (18-50 Yaş)'],
    ];

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $instructorFilter = $this->option('instructor');
        $programIdFilter = $this->option('program-id');

        $this->normalizeHalkOyunlariTitles($dryRun);

        $programs = EducationProgram::withCount('sessions')
            ->with('sessions')
            ->when($programIdFilter, fn ($q) => $q->where('id', $programIdFilter))
            ->when($instructorFilter, fn ($q) => $q->where('instructor', 'like', '%' . $instructorFilter . '%'))
            ->get()
            ->filter(fn ($program) => $program->sessions_count > 1);

        if ($programs->isEmpty()) {
            $this->info('İşlenecek çok günlü program bulunamadı.');
            return self::SUCCESS;
        }

        $this->info($dryRun ? 'Önizleme modu (değişiklik yapılmayacak):' : 'Birleştirme başlıyor...');
        if ($instructorFilter) {
            $this->line("Filtre: eğitmen = {$instructorFilter}");
        }
        if ($programIdFilter) {
            $this->line("Filtre: program ID = {$programIdFilter}");
        }
        $this->newLine();

        $totalUpdated = 0;

        foreach ($programs as $program) {
            $applications = Application::where('education_program_id', $program->id)->get();
            $total = $applications->count();

            if ($total === 0) {
                continue;
            }

            $bySession = $applications->groupBy(fn ($app) => $app->session_id ?? 'null');
            $withSession = $applications->whereNotNull('session_id')->count();

            $this->line("▸ [Program #{$program->id}] {$program->title}");
            $this->line("  Eğitmen: {$program->instructor} | Yaş: {$program->age_range} | Yer: {$program->location}");
            $this->line("  Toplam kayıt: {$total}/{$program->capacity}");

            foreach ($bySession as $sessionKey => $group) {
                $label = $sessionKey === 'null'
                    ? 'Gün atanmamış (birleşik)'
                    : $this->sessionLabel($program, (int) $sessionKey);
                $this->line("    • {$label}: {$group->count()} kayıt");
            }

            if ($withSession > 0) {
                if (!$dryRun) {
                    DB::table('applications')
                        ->where('education_program_id', $program->id)
                        ->whereNotNull('session_id')
                        ->update(['session_id' => null]);
                }
                $totalUpdated += $withSession;
                $this->info($dryRun
                    ? "  → {$withSession} kayıt bu programda birleştirilecek"
                    : "  ✓ {$withSession} kayıt birleştirildi → {$program->instructor}: {$total}/{$program->capacity}");
            } else {
                $this->comment('  Zaten birleşik, işlem gerekmedi.');
            }

            $this->newLine();
        }

        if ($dryRun) {
            $this->warn("Toplam {$totalUpdated} kayıt birleştirilecek.");
            $this->line('Uygulamak için: php artisan applications:consolidate-multi-day');
            $this->line('Sadece Aydın Elbasan için: php artisan applications:consolidate-multi-day --instructor="Aydın Elbasan"');
        } else {
            $this->info("Tamamlandı. {$totalUpdated} kayıt birleştirildi.");
        }

        return self::SUCCESS;
    }

    private function normalizeHalkOyunlariTitles(bool $dryRun): void
    {
        $updated = 0;

        foreach (self::HALK_OYUNLARI_TITLES as $row) {
            $query = EducationProgram::query()
                ->where('instructor', $row['instructor'])
                ->where('age_range', $row['age_range'])
                ->where('title', '!=', $row['title']);

            $count = $query->count();

            if ($count > 0) {
                if (!$dryRun) {
                    $query->update(['title' => $row['title']]);
                }
                $updated += $count;
                $this->line(($dryRun ? 'Önizleme: ' : '') . "Program adı güncellenecek → {$row['title']}");
            }
        }

        if ($updated > 0) {
            $this->newLine();
        }
    }

    private function sessionLabel(EducationProgram $program, int $sessionId): string
    {
        $session = $program->sessions->firstWhere('id', $sessionId);

        if (!$session) {
            return "Session #{$sessionId}";
        }

        return "{$session->day} " . substr($session->start_time, 0, 5) . '-' . substr($session->end_time, 0, 5);
    }
}
