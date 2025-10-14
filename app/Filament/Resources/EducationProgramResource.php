<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EducationProgramResource\Pages;
use App\Models\EducationProgram;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;

class EducationProgramResource extends Resource
{
    protected static ?string $model = EducationProgram::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Eğitim Programları';
    protected static ?string $pluralModelLabel = 'Eğitim Programları';
    protected static ?string $modelLabel = 'Eğitim Programı';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount('applications');
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            TextInput::make('title')
                ->label('Program Adı')
                ->required()
                ->maxLength(255),

            TextInput::make('age_range')
                ->label('Yaş Aralığı')
                ->required()
                ->maxLength(255),

            TextInput::make('capacity')
                ->label('Kapasite')
                ->numeric()
                ->minValue(1)
                ->required(),

            Toggle::make('is_open')
                ->label('Başvuruya Açık mı?'),

            Toggle::make('is_custom_schedule')
                ->label('Saat ve Kontenjan Müdürlük Tarafından Belirlenecek')
                ->default(false)
                ->helperText('Bu seçeneği işaretlerseniz saat aralıkları eklemeniz gerekmez.'),

            // ⏰ Saat Aralıkları sadece özel olmayan kurslarda gözükecek
            Repeater::make('sessions')
                ->label('Saat Aralıkları')
                ->relationship()
                ->schema([
                    Select::make('day')
                        ->label('Gün')
                        ->options([
                            'Pazartesi' => 'Pazartesi',
                            'Salı' => 'Salı',
                            'Çarşamba' => 'Çarşamba',
                            'Perşembe' => 'Perşembe',
                            'Cuma' => 'Cuma',
                            'Cumartesi' => 'Cumartesi',
                            'Pazar' => 'Pazar',
                        ])
                        ->required(),

                    TimePicker::make('start_time')
                        ->label('Başlangıç Saati')
                        ->required(),

                    TimePicker::make('end_time')
                        ->label('Bitiş Saati')
                        ->required(),

                    TextInput::make('quota')
                        ->label('Kontenjan')
                        ->numeric()
                        ->default(10)
                        ->required(),
                ])
                ->visible(fn ($get) => ! $get('is_custom_schedule'))
                ->orderable()
                ->collapsible()
                ->createItemButtonLabel('Yeni Saat Aralığı Ekle'),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            TextColumn::make('title')->label('Program Adı')->searchable(),
            TextColumn::make('age_range')->label('Yaş Aralığı'),
            TextColumn::make('capacity')->label('Kapasite'),
            TextColumn::make('applications_count')->label('Başvuru Sayısı')
                ->suffix(fn ($state, $record) => "/{$record->capacity}"),

            BadgeColumn::make('is_custom_schedule')
                ->label('Plan Türü')
                ->formatStateUsing(fn ($state) => $state ? 'Müdürlük Belirleyecek' : 'Standart Saatli')
                ->colors([
                    'info' => fn ($state) => $state,
                    'success' => fn ($state) => ! $state,
                ]),

            BadgeColumn::make('is_open')
                ->label('Durum')
                ->formatStateUsing(fn ($state) => $state ? 'Açık' : 'Kapalı')
                ->colors([
                    'success' => fn ($state) => $state,
                    'danger' => fn ($state) => ! $state,
                ]),
        ])
        ->actions([
            EditAction::make(),
            DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Eğitim Programını Sil')
                ->modalSubheading('Bu programı silmek istediğinize emin misiniz?'),
        ])
        ->bulkActions([
            DeleteBulkAction::make()
                ->requiresConfirmation()
                ->modalHeading('Seçili Programları Sil')
                ->modalSubheading('Bu programları kalıcı olarak silmek istediğinizden emin misiniz?'),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index'   => Pages\ListEducationPrograms::route('/'),
            'create'  => Pages\CreateEducationProgram::route('/create'),
            'edit'    => Pages\EditEducationProgram::route('/{record}/edit'),
        ];
    }
}
