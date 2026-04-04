<?php

namespace App\Filament\Resources\MaintenanceLogs\Pages;

use App\Filament\Resources\MaintenanceLogs\MaintenanceLogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMaintenanceLog extends CreateRecord
{
    protected static string $resource = MaintenanceLogResource::class;
}
