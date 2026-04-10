<?php

namespace App\Filament\Resources\MachineReadings\Pages;

use App\Filament\Resources\MachineReadings\MachineReadingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMachineReading extends EditRecord
{
    protected static string $resource = MachineReadingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
