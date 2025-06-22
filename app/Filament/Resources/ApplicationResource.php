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
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationApprovedMail;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\View;
use Barryvdh\DomPDF\Facade\Pdf;



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
            Forms\Components\TextInput::make('first_name')->label('Ad'),
            Forms\Components\TextInput::make('last_name')->label('Soyad'),
            Forms\Components\TextInput::make('tc_no')->label('TC No'),
            Forms\Components\DatePicker::make('birth_date')->label('Doğum Tarihi'),
            Forms\Components\TextInput::make('phone')->label('Telefon'),
            Forms\Components\TextInput::make('parent_name')->label('Veli Adı'),
            Forms\Components\TextInput::make('parent_phone')->label('Veli Telefonu'),
            Forms\Components\Select::make('education_program_id')
                ->label('Eğitim Programı')
                ->relationship('educationProgram', 'title'),
            Forms\Components\Toggle::make('is_approved')->label('Onay Durumu'),

            // 👇 Bu satır, base64 imza verisini gösterir (geçici kontrol için)
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
            TextColumn::make('educationProgram.title')->label('Eğitim'),
            TextColumn::make('first_name')->label('Ad'),
            TextColumn::make('last_name')->label('Soyad'),
            TextColumn::make('tc_no')->label('TC'),
            TextColumn::make('phone')->label('Telefon'),
            TextColumn::make('parent_name')->label('Veli Ad Soyad'),
            TextColumn::make('parent_phone')->label('Veli Telefon'),
            BooleanColumn::make('is_approved')->label('Onaylı mı?'),
        ])
        ->filters([
            SelectFilter::make('education_program_id')
                ->label('Eğitim Programı')
                ->relationship('educationProgram', 'title'),
        ]);
        
}


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApplications::route('/'),
            'create' => Pages\CreateApplication::route('/create'),
            'edit' => Pages\EditApplication::route('/{record}/edit'),
        ];
    }
    
    public static function getHeaderActions(): array
{
    return [
        Action::make('pdf-export')
            ->label('Onaylı Başvuruları PDF İndir')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('success')
            ->action(function () {
                $applications = \App\Models\Application::with('educationProgram')
                    ->where('is_approved', true)
                    ->get();

                $pdf = Pdf::loadView('exports.applications_pdf', [
                    'applications' => $applications
                ]);

                return response()->streamDownload(
                    fn () => print($pdf->stream()),
                    'onayli-basvurular.pdf'
                );
            }),
    ];
}

}
