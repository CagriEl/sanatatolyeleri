<?php

namespace App\Console\Commands;

use App\Models\Application;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MonitorApplications extends Command
{
    protected $signature = 'monitor:applications {--dry-run : Mail göndermeden raporu yazdır}';

    protected $description = 'Başvuru düşüşü ve log hatalarını kontrol eder, gerekirse e-posta gönderir';

    public function handle(): int
    {
        $alerts = [];

        $currentWindow = Application::where('created_at', '>=', now()->subHours(2))->count();
        $previousWindow = Application::whereBetween('created_at', [now()->subHours(4), now()->subHours(2)])->count();

        if ($previousWindow >= 5 && $currentWindow < max(1, (int) floor($previousWindow * 0.2))) {
            $alerts[] = "Başvuru düşüşü: Son 2 saatte {$currentWindow} başvuru var, önceki 2 saatte {$previousWindow} başvuruydu.";
        }

        $errorCount = $this->countRecentLogErrors();
        if ($errorCount >= 10) {
            $alerts[] = "Log hatası: Son 1 saatte yaklaşık {$errorCount} ERROR kaydı bulundu.";
        }

        $today = Application::whereDate('created_at', today())->count();
        $openPrograms = \App\Models\EducationProgram::where('is_open', true)->count();

        $summary = [
            "Bugünkü başvuru: {$today}",
            "Son 2 saat: {$currentWindow}",
            "Önceki 2 saat: {$previousWindow}",
            "Açık program: {$openPrograms}",
            "Son 1 saat ERROR: {$errorCount}",
        ];

        foreach ($summary as $line) {
            $this->line($line);
        }

        if (empty($alerts)) {
            $this->info('Uyarı yok.');
            return self::SUCCESS;
        }

        foreach ($alerts as $alert) {
            $this->warn($alert);
            Log::warning('[monitor:applications] ' . $alert);
        }

        $recipient = config('services.monitor.email') ?: env('MONITOR_EMAIL');

        if (! $recipient) {
            $this->error('MONITOR_EMAIL tanımlı değil; mail gönderilmedi.');
            return self::SUCCESS;
        }

        if ($this->option('dry-run')) {
            $this->comment('Dry-run: mail gönderilmedi → ' . $recipient);
            return self::SUCCESS;
        }

        $body = "Kırklareli Başvuru İzleme Uyarısı\n\n"
            . implode("\n", $alerts)
            . "\n\nÖzet:\n- "
            . implode("\n- ", $summary)
            . "\n\nTarih: " . now()->format('d.m.Y H:i');

        Mail::raw($body, function ($message) use ($recipient) {
            $message->to($recipient)
                ->subject('[Uyarı] Başvuru sistemi izleme');
        });

        $this->info('Uyarı maili gönderildi: ' . $recipient);

        return self::SUCCESS;
    }

    private function countRecentLogErrors(): int
    {
        $path = storage_path('logs/laravel.log');

        if (! is_file($path)) {
            return 0;
        }

        $cutoff = now()->subHour();
        $count = 0;
        $handle = fopen($path, 'r');

        if (! $handle) {
            return 0;
        }

        // Dosya sonundan okumak için basit yaklaşım: son 2MB
        $size = filesize($path);
        $start = max(0, $size - 2_000_000);
        fseek($handle, $start);
        if ($start > 0) {
            fgets($handle); // kısmi satırı atla
        }

        while (($line = fgets($handle)) !== false) {
            if (! str_contains($line, '.ERROR:')) {
                continue;
            }

            if (preg_match('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]/', $line, $matches)) {
                try {
                    if (\Carbon\Carbon::parse($matches[1])->gte($cutoff)) {
                        $count++;
                    }
                } catch (\Throwable) {
                    // ignore parse errors
                }
            }
        }

        fclose($handle);

        return $count;
    }
}
