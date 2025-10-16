<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplicationResource\Pages;
use App\Models\Application;
use App\Models\EducationSession;
use App\Models\EducationProgram;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;

// Excel export
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Maatwebsite\Excel\Excel;

class ApplicationResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Kayıtlar';
    protected static ?string $pluralModelLabel = 'Kayıtlar';
    protected static ?string $modelLabel = 'Kayıt';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('first_name')->label('Ad'),
                TextInput::make('last_name')->label('Soyad'),
                TextInput::make('email')->label('E-Posta')->email()->required(),
                TextInput::make('tc_no')->label('TC No'),
                DatePicker::make('birth_date')->label('Doğum Tarihi'),
                TextInput::make('phone')->label('Telefon'),
                TextInput::make('parent_name')->label('Veli Adı'),
                TextInput::make('parent_phone')->label('Veli Telefonu'),

                Select::make('education_program_id')
                    ->label('Eğitim Programı')
                    ->relationship('educationProgram', 'title')
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('session_id', null)),

                Select::make('session_id')
                    ->label('Saat Aralığı')
                    ->options(function (callable $get) {
                        $programId = $get('education_program_id');

                        if (!$programId) return [];

                        $program = EducationProgram::find($programId);

                        // Müdürlük belirleyecekse boş döndür
                        if ($program && $program->is_custom_schedule) {
                            return [];
                        }

                        return EducationSession::where('education_program_id', $programId)
                            ->orderBy('start_time')
                            ->get()
                            ->mapWithKeys(fn ($s) => [
                                $s->id => "{$s->day} | " . substr($s->start_time, 0, 5) . " - " . substr($s->end_time, 0, 5),
                            ])
                            ->toArray();
                    })
                    ->searchable()
                    ->nullable() // 👈 Boş bırakılabilir
                    ->placeholder('Saat seçiniz (isteğe bağlı)')
                    ->hidden(function (callable $get) {
    $programId = $get('education_program_id');
    if (!$programId) {
        return false;
    }

    $program = EducationProgram::find($programId);
    return $program && $program->is_custom_schedule;
})
                    ->helperText('Müdürlük belirleyecek kurslarda bu alan boş bırakılabilir.'),

                Forms\Components\Toggle::make('is_approved')
                    ->label('Onay Durumu'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name')->label('Ad')->searchable(),
                TextColumn::make('last_name')->label('Soyad')->searchable(),
                TextColumn::make('email')->label('E-Posta')->searchable(),
                TextColumn::make('educationProgram.title')->label('Eğitim')->toggleable(),

                TextColumn::make('session')
                    ->label('Saat Aralığı')
                    ->getStateUsing(fn ($record) =>
                        $record->session
                            ? "{$record->session->day} | " . substr($record->session->start_time, 0, 5) . " - " . substr($record->session->end_time, 0, 5)
                            : 'Müdürlük Belirleyecek'
                    )
                    ->toggleable(),

                TextColumn::make('tc_no')->label('TC')->toggleable(),
                TextColumn::make('phone')->label('Telefon')->toggleable(),
                BooleanColumn::make('is_approved')->label('Onaylı mı?')->toggleable(),
                TextColumn::make('created_at')->label('Kayıt Tarihi')->dateTime('d.m.Y H:i')->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                SelectFilter::make('education_program_id')
                    ->label('Eğitim Programı')
                    ->relationship('educationProgram', 'title'),

                SelectFilter::make('session_id')
                    ->label('Saat Aralığı')
                    ->options(fn () =>
                        EducationSession::orderBy('start_time')
                            ->get()
                            ->mapWithKeys(fn ($s) => [
                                $s->id => "{$s->day} | " . substr($s->start_time, 0, 5) . " - " . substr($s->end_time, 0, 5),
                            ])
                    )
                    ->searchable()
                    ->preload(),
            ])

            ->headerActions([
                ExportAction::make('excel-export')
                    ->label('Filtreli Excel İndir')
                    ->color('success')
                    ->icon('heroicon-o-document-arrow-down')
                    ->exports([
                        ExcelExport::make('Kayıtlar')
                            ->fromTable()
                            ->withWriterType(Excel::XLSX)
                            ->withFilename(fn () => 'basvuru_listesi_' . now()->format('Y-m-d_H-i')),
                    ]),
            ])

            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListApplications::route('/'),
            'create' => Pages\CreateApplication::route('/create'),
            'edit'   => Pages\EditApplication::route('/{record}/edit'),
        ];
    }

    public static function getHeaderActions(): array
    {
        return [
            Action::make('pdf-export')
                ->label('Onaylı Başvuruları PDF İndir')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('primary')
                ->action(function () {
                    $applications = Application::with(['educationProgram', 'session'])
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
