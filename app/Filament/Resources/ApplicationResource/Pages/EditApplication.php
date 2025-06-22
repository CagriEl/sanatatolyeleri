<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Filament\Resources\ApplicationResource;
use App\Mail\ApplicationApprovedMail;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Mail;

class EditApplication extends EditRecord
{
    protected static string $resource = ApplicationResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $wasApproved = $this->record->is_approved;
        $willBeApproved = $data['is_approved'] ?? false;

        if (!$wasApproved && $willBeApproved) {
            // Mail::to($this->record->phone . '@mailtrap.io')->send(
            //     new ApplicationApprovedMail($this->record)
            // );
        }

        return $data;
    }
}
