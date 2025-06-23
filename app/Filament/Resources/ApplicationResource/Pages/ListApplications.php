<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Filament\Resources\ApplicationResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Response;

class ListApplications extends ListRecords
{
    protected static string $resource = ApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportApprovedPdf')
                ->label('Onaylı Başvuruları PDF İndir')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->requiresConfirmation()
                ->action(function () {
                    // 1) Filtreli + onaylı başvuruları al
                    $applications = $this
                        ->getFilteredTableQuery()
                        ->where('is_approved', true)
                        ->with('educationProgram')
                        ->get();

                    // 2) PDF’i oluştur
                    $pdf = Pdf::loadView('exports.applications_pdf', [
                        'applications' => $applications,
                    ]);

                    // 3) Streamed download Response döndür
                    return Response::streamDownload(
                        fn () => print($pdf->output()),
                        'onayli-basvurular.pdf',
                        [
                            'Content-Type'        => 'application/pdf',
                            'Content-Disposition' => 'attachment; filename="onayli-basvurular.pdf"',
                        ],
                    );
                }),
        ];
    }
}
