<?php

namespace App\Filament\Resources\ServiceOrders\Pages;

use App\Filament\Resources\ServiceOrders\ServiceOrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateServiceOrder extends CreateRecord
{
    protected static string $resource = ServiceOrderResource::class;
}
