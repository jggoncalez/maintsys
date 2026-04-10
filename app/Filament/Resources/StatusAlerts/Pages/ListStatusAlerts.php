<?php

namespace App\Filament\Resources\StatusAlerts\Pages;

use App\Filament\Resources\StatusAlerts\StatusAlertResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStatusAlerts extends ListRecords
{
    protected static string $resource = StatusAlertResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
