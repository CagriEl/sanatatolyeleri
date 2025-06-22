<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Filament\Resources\ApplicationResource;
use App\Models\Application;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApplications extends ListRecords
{
    protected static string $resource = ApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('pdf-export')
                ->label('Onaylı Başvuruları PDF İndir')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    $applications = Application::with('educationProgram')
                        ->where('is_approved', true)
                        ->get();

                    $pdf = Pdf::loadView('exports.applications_pdf', [
                        'applications' => $applications,
                    ]);

                    return response()->streamDownload(
                        fn () => print($pdf->stream()),
                        'onayli-basvurular.pdf'
                    );
                }),
        ];
    }
}
