<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplicationResource\Pages;
use App\Models\Application;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\View;
use Barryvdh\DomPDF\Facade\Pdf;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction as ExcelExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
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
        return $form->schema([
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
                ->relationship('educationProgram', 'title'),
            Select::make('session_id')
                ->label('Saat Aralığı')
                ->relationship('session', 'time_range'),
            Forms\Components\Toggle::make('is_approved')->label('Onay Durumu'),
            View::make('components.signature-preview')
                ->label('İmza Önizleme')
                ->visible(fn ($record) => filled($record?->signature))
                ->columnSpanFull(),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name')->label('Ad'),
                TextColumn::make('last_name')->label('Soyad'),
                TextColumn::make('email')->label('E-Posta'),
                TextColumn::make('educationProgram.title')->label('Eğitim'),
                TextColumn::make('session.time_range')->label('Saat Aralığı'),
                TextColumn::make('tc_no')->label('TC'),
                TextColumn::make('phone')->label('Telefon'),
                BooleanColumn::make('is_approved')->label('Onaylı mı?'),
            ])
            ->filters([
                SelectFilter::make('education_program_id')
                    ->label('Eğitim Programı')
                    ->relationship('educationProgram', 'title'),
            ])
            ->headerActions([
                ExcelExportAction::make('export-xlsx')
                    ->label('Excel Dışa Aktar')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->exports([
                        ExcelExport::make('Kayıtlar')
                            ->fromTable()
                            ->withWriterType(Excel::XLSX)
                            ->withFilename(fn () => 'BasvuruListesi_' . now()->format('Y-m-d_H-i')),
                    ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                ExportBulkAction::make('export-selected')
                    ->label('Seçilileri Dışa Aktar')
                    ->exports([
                        ExcelExport::make('Seçililer')
                            ->fromTable()
                            ->withWriterType(Excel::XLSX)
                            ->withFilename(fn () => 'BasvuruListesi_Secili_' . now()->format('Y-m-d_H-i')),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListApplications::route('/'),
            'create' => Pages\CreateApplication::route('/create'),
            'edit'   => Pages\EditApplication::route('/{record}/edit'),
        ];
    }
}
