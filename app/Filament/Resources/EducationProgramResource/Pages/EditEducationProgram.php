<?php

namespace App\Filament\Resources\EducationProgramResource\Pages;

use App\Filament\Resources\EducationProgramResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEducationProgram extends EditRecord
{
    protected static string $resource = EducationProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
