<?php

namespace App\Filament\Resources;

use App\Models\ServiceOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ServiceOrderResource extends Resource
{
    protected static ?string $model = ServiceOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench';

    protected static ?string $navigationGroup = 'Ordens de Serviço';

    protected static ?string $label = 'Ordens de Serviço';

    protected static ?string $pluralLabel = 'Ordens de Serviço';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Título')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Select::make('machine_id')
                    ->label('Máquina')
                    ->relationship('machine', 'name')
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('technician_id')
                    ->label('Técnico')
                    ->relationship('technician', 'name', fn ($query) => $query->role('tecnico'))
                    ->required(),

                Forms\Components\Select::make('type')
                    ->label('Tipo')
                    ->options([
                        'preventive' => 'Preventiva',
                        'corrective' => 'Corretiva',
                    ])
                    ->required(),

                Forms\Components\Select::make('priority')
                    ->label('Prioridade')
                    ->options([
                        'low' => 'Baixa',
                        'medium' => 'Média',
                        'high' => 'Alta',
                        'critical' => 'Crítica',
                    ])
                    ->default('medium')
                    ->required(),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'open' => 'Aberta',
                        'in_progress' => 'Em Progresso',
                        'completed' => 'Concluída',
                        'cancelled' => 'Cancelada',
                    ])
                    ->default('open')
                    ->required(),

                Forms\Components\Textarea::make('description')
                    ->label('Descrição')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('resolution_notes')
                    ->label('Notas de Resolução')
                    ->visible(fn ($get) => $get('status') === 'completed')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable(),
                Tables\Columns\TextColumn::make('machine.name')
                    ->label('Máquina')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Tipo')
                    ->getStateUsing(fn ($record) => match ($record->type) {
                        'preventive' => 'Preventiva',
                        'corrective' => 'Corretiva',
                        default => $record->type,
                    })
                    ->color(fn ($state) => match ($state) {
                        'Preventiva' => 'info',
                        'Corretiva' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\BadgeColumn::make('priority')
                    ->label('Prioridade')
                    ->getStateUsing(fn ($record) => match ($record->priority) {
                        'low' => 'Baixa',
                        'medium' => 'Média',
                        'high' => 'Alta',
                        'critical' => 'Crítica',
                        default => $record->priority,
                    })
                    ->color(fn ($state) => match ($state) {
                        'Baixa' => 'success',
                        'Média' => 'info',
                        'Alta' => 'warning',
                        'Crítica' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(fn ($record) => match ($record->status) {
                        'open' => 'Aberta',
                        'in_progress' => 'Em Progresso',
                        'completed' => 'Concluída',
                        'cancelled' => 'Cancelada',
                        default => $record->status,
                    })
                    ->color(fn ($state) => match ($state) {
                        'Aberta' => 'info',
                        'Em Progresso' => 'warning',
                        'Concluída' => 'success',
                        'Cancelada' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('technician.name')
                    ->label('Técnico'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criada em')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'open' => 'Aberta',
                        'in_progress' => 'Em Progresso',
                        'completed' => 'Concluída',
                        'cancelled' => 'Cancelada',
                    ]),
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipo')
                    ->options([
                        'preventive' => 'Preventiva',
                        'corrective' => 'Corretiva',
                    ]),
                Tables\Filters\SelectFilter::make('priority')
                    ->label('Prioridade')
                    ->options([
                        'low' => 'Baixa',
                        'medium' => 'Média',
                        'high' => 'Alta',
                        'critical' => 'Crítica',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Visualizar'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('start')
                    ->label('Iniciar O.S.')
                    ->icon('heroicon-o-play')
                    ->color('warning')
                    ->visible(fn ($record) => $record->isOpen())
                    ->action(function (ServiceOrder $record) {
                        $record->start();
                    }),
                Tables\Actions\Action::make('complete')
                    ->label('Concluir O.S.')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'in_progress')
                    ->form([
                        Forms\Components\Textarea::make('resolution_notes')
                            ->label('Notas de Resolução')
                            ->required(),
                    ])
                    ->action(function (ServiceOrder $record, array $data) {
                        $record->complete($data['resolution_notes']);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'gerente', 'tecnico']);
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'gerente']);
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\ServiceOrderResource\Pages\ListServiceOrders::route('/'),
            'create' => \App\Filament\Resources\ServiceOrderResource\Pages\CreateServiceOrder::route('/create'),
            'view' => \App\Filament\Resources\ServiceOrderResource\Pages\ViewServiceOrder::route('/{record}'),
            'edit' => \App\Filament\Resources\ServiceOrderResource\Pages\EditServiceOrder::route('/{record}/edit'),
        ];
    }
}
