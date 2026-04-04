<?php

namespace App\Filament\Resources\StatusAlertResource\Pages;

use App\Filament\Resources\StatusAlertResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewStatusAlert extends ViewRecord
{
    protected static string $resource = StatusAlertResource::class;

    public function getTitle(): string|Htmlable
    {
        return 'Visualizar Alerta de Status';
    }

    protected function getHeaderActions(): array
    {
        $record = $this->getRecord();

        return [
            Actions\Action::make('toggle_read')
                ->label($record->is_read ? 'Marcar como Não Lido' : 'Marcar como Lido')
                ->icon($record->is_read ? 'heroicon-o-x-mark' : 'heroicon-o-check')
                ->color($record->is_read ? 'danger' : 'success')
                ->action(fn () => $record->update(['is_read' => !$record->is_read]) ?? true),
        ];
    }
}
