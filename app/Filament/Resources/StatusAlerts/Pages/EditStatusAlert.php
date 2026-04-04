<?php

namespace App\Filament\Resources\StatusAlerts\Pages;

use App\Filament\Resources\StatusAlerts\StatusAlertResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStatusAlert extends EditRecord
{
    protected static string $resource = StatusAlertResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
