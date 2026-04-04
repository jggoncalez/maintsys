<?php

namespace App\Filament\Resources\ServiceOrderResource\Pages;

use App\Filament\Resources\ServiceOrderResource;
use App\Models\ServiceOrder;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewServiceOrder extends ViewRecord
{
    protected static string $resource = ServiceOrderResource::class;

    public function getTitle(): string|Htmlable
    {
        return 'Visualizar Ordem de Serviço';
    }

    protected function getHeaderActions(): array
    {
        $record = $this->getRecord();

        return [
            Actions\EditAction::make()
                ->label('Editar')
                ->visible(fn () => auth()->user()->hasAnyRole(['admin', 'gerente'])),

            Actions\Action::make('start')
                ->label('Iniciar')
                ->icon('heroicon-o-play')
                ->color('warning')
                ->visible(fn () => $record->isOpen())
                ->action(fn () => $record->start() ?? true)
                ->requiresConfirmation(),

            Actions\Action::make('complete')
                ->label('Concluir')
                ->icon('heroicon-o-check')
                ->color('success')
                ->visible(fn () => $record->status === 'in_progress')
                ->form([
                    \Filament\Forms\Components\Textarea::make('resolution_notes')
                        ->label('Notas de Resolução')
                        ->required(),
                ])
                ->action(fn (array $data) => $record->complete($data['resolution_notes']) ?? true),

            Actions\DeleteAction::make()
                ->label('Deletar')
                ->visible(fn () => auth()->user()->hasRole('admin')),
        ];
    }
}
