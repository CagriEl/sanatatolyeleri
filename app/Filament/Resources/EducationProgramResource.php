<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EducationProgramResource\Pages;
use App\Models\EducationProgram;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;

class EducationProgramResource extends Resource
{
    protected static ?string $model = EducationProgram::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Eğitim Programları';
    protected static ?string $pluralModelLabel = 'Eğitim Programları';
    protected static ?string $modelLabel = 'Eğitim Programı';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
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
                    ->label('Başvuru Alınıyor')
                    ->inline(false), // dikey gösterim
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
{
    return $table
        ->columns([
            TextColumn::make('title')->label('Program Adı')->searchable(),
            TextColumn::make('age_range')->label('Yaş Aralığı'),
            TextColumn::make('capacity')->label('Kapasite'),

            Tables\Columns\BooleanColumn::make('is_open')
                ->label('Başvuru Durumu')
                ->trueIcon('heroicon-o-check-circle')
                ->falseIcon('heroicon-o-x-circle')
                ->trueColor('success')
                ->falseColor('danger')
                ->alignCenter(),
        ])
        ->defaultSort('id', 'asc');
}


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEducationPrograms::route('/'),
            'create' => Pages\CreateEducationProgram::route('/create'),
            'edit' => Pages\EditEducationProgram::route('/{record}/edit'),
        ];
    }
}
