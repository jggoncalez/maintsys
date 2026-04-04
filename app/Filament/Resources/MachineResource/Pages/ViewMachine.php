<?php

namespace App\Filament\Resources\MachineResource\Pages;

use App\Filament\Resources\MachineResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewMachine extends ViewRecord
{
    protected static string $resource = MachineResource::class;

    public function getTitle(): string|Htmlable
    {
        return 'Visualizar Máquina';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Editar'),
            Actions\DeleteAction::make()
                ->label('Deletar')
                ->visible(fn () => auth()->user()->hasRole('admin')),
        ];
    }
}
