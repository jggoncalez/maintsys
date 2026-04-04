<?php

namespace App\Filament\Resources\MaintenanceLogResource\Pages;

use App\Filament\Resources\MaintenanceLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewMaintenanceLog extends ViewRecord
{
    protected static string $resource = MaintenanceLogResource::class;

    public function getTitle(): string|Htmlable
    {
        return 'Visualizar Log de Manutenção';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Editar')
                ->visible(fn () => auth()->user()->hasAnyRole(['admin', 'gerente'])),

            Actions\DeleteAction::make()
                ->label('Deletar')
                ->visible(fn () => auth()->user()->hasAnyRole(['admin', 'gerente'])),
        ];
    }
}
