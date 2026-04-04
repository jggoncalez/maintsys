<?php

namespace App\Filament\Resources;

use App\Models\StatusAlert;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StatusAlertResource extends Resource
{
    protected static ?string $model = StatusAlert::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell';

    protected static ?string $navigationGroup = 'Alertas';

    protected static ?string $label = 'Alertas de Status';

    protected static ?string $pluralLabel = 'Alertas de Status';

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Read-only form - no fields
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('machine.name')
                    ->label('Máquina')
                    ->searchable(),
                Tables\Columns\TextColumn::make('message')
                    ->label('Mensagem'),
                Tables\Columns\TextColumn::make('previous_status')
                    ->label('Status Anterior'),
                Tables\Columns\BadgeColumn::make('new_status')
                    ->label('Novo Status')
                    ->getStateUsing(fn ($record) => match ($record->new_status) {
                        'operational' => 'Operacional',
                        'maintenance' => 'Em Manutenção',
                        'critical' => 'Crítica',
                        'offline' => 'Offline',
                        default => $record->new_status,
                    })
                    ->color(fn ($state) => match ($state) {
                        'Operacional' => 'success',
                        'Em Manutenção' => 'warning',
                        'Crítica' => 'danger',
                        'Offline' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_read')
                    ->label('Lido')
                    ->boolean(),
                Tables\Columns\TextColumn::make('triggered_at')
                    ->label('Disparado em')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('machine_id')
                    ->label('Máquina')
                    ->relationship('machine', 'name'),
                Tables\Filters\SelectFilter::make('new_status')
                    ->label('Novo Status')
                    ->options([
                        'operational' => 'Operacional',
                        'maintenance' => 'Em Manutenção',
                        'critical' => 'Crítica',
                        'offline' => 'Offline',
                    ]),
                Tables\Filters\TernaryFilter::make('is_read')
                    ->label('Lido'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Visualizar'),
                Tables\Actions\Action::make('toggle_read')
                    ->label('Marcar como Lido')
                    ->icon('heroicon-o-check')
                    ->action(function (StatusAlert $record) {
                        $record->update(['is_read' => !$record->is_read]);
                    })
                    ->hidden(fn ($record) => $record->is_read),
                Tables\Actions\Action::make('toggle_unread')
                    ->label('Marcar como Não Lido')
                    ->icon('heroicon-o-x-mark')
                    ->action(function (StatusAlert $record) {
                        $record->update(['is_read' => !$record->is_read]);
                    })
                    ->hidden(fn ($record) => !$record->is_read),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\StatusAlertResource\Pages\ListStatusAlerts::route('/'),
            'view' => \App\Filament\Resources\StatusAlertResource\Pages\ViewStatusAlert::route('/{record}'),
        ];
    }
}
