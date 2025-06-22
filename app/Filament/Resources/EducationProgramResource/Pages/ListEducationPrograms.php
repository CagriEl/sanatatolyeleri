<?php

namespace App\Filament\Resources\EducationProgramResource\Pages;

use App\Filament\Resources\EducationProgramResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEducationPrograms extends ListRecords
{
    protected static string $resource = EducationProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
