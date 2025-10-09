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
        return $form
            ->schema([
                // 📘 Eğitim Bilgileri
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
                    ->required()
                    ->numeric()
                    ->minValue(1),

                Toggle::make('is_open')
                    ->label('Başvuruya Açık mı?'),

                // ⏰ Saat Aralıkları Repeater Alanı
                Repeater::make('sessions')
                    ->label('Saat Aralıkları')
                    ->relationship()
                    ->schema([
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
                    ->orderable()
                    ->collapsible()
                    ->createItemButtonLabel('Yeni Saat Aralığı Ekle'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Program Adı')
                    ->searchable(),

                TextColumn::make('age_range')
                    ->label('Yaş Aralığı')
                    ->formatStateUsing(fn (string $state): string => "{$state} Yaş"),

                TextColumn::make('capacity')
                    ->label('Toplam Kapasite'),

                TextColumn::make('applications_count')
                    ->label('Başvuru Sayısı')
                    ->suffix(fn ($state, $record) => "/{$record->capacity}"),

                BadgeColumn::make('is_full')
                    ->label('Durum')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'KONTENJAN DOLU' : 'AÇIK')
                    ->colors([
                        'danger'  => fn (bool $state): bool => $state,
                        'success' => fn (bool $state): bool => ! $state,
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
            ])
            ->defaultSort('id', 'asc');
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
