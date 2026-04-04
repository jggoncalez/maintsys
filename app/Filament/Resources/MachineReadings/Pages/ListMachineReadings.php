<?php

namespace App\Filament\Resources\MachineReadings\Pages;

use App\Filament\Resources\MachineReadings\MachineReadingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMachineReadings extends ListRecords
{
    protected static string $resource = MachineReadingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
