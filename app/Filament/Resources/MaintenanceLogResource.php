<?php

namespace App\Filament\Resources;

use App\Models\MaintenanceLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MaintenanceLogResource extends Resource
{
    protected static ?string $model = MaintenanceLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Ordens de Serviço';

    protected static ?string $label = 'Logs de Manutenção';

    protected static ?string $pluralLabel = 'Logs de Manutenção';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('machine_id')
                    ->label('Máquina')
                    ->relationship('machine', 'name')
                    ->searchable()
                    ->required()
                    ->reactive(),

                Forms\Components\Select::make('service_order_id')
                    ->label('Ordem de Serviço')
                    ->relationship(
                        'serviceOrder',
                        'title',
                        fn ($query, $get) => $query->where('machine_id', $get('machine_id'))
                    )
                    ->searchable(),

                Forms\Components\TextInput::make('action')
                    ->label('Ação')
                    ->required(),

                Forms\Components\TextInput::make('defect_type')
                    ->label('Tipo de Defeito'),

                Forms\Components\DateTimePicker::make('logged_at')
                    ->label('Data e Hora')
                    ->default(now())
                    ->required(),

                Forms\Components\Textarea::make('description')
                    ->label('Descrição')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('machine.name')
                    ->label('Máquina')
                    ->searchable(),
                Tables\Columns\TextColumn::make('action')
                    ->label('Ação')
                    ->searchable(),
                Tables\Columns\TextColumn::make('defect_type')
                    ->label('Tipo de Defeito'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuário'),
                Tables\Columns\TextColumn::make('logged_at')
                    ->label('Data e Hora')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('machine_id')
                    ->label('Máquina')
                    ->relationship('machine', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Visualizar'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
        return auth()->user()->hasAnyRole(['admin', 'gerente']);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\MaintenanceLogResource\Pages\ListMaintenanceLogs::route('/'),
            'create' => \App\Filament\Resources\MaintenanceLogResource\Pages\CreateMaintenanceLog::route('/create'),
            'view' => \App\Filament\Resources\MaintenanceLogResource\Pages\ViewMaintenanceLog::route('/{record}'),
            'edit' => \App\Filament\Resources\MaintenanceLogResource\Pages\EditMaintenanceLog::route('/{record}/edit'),
        ];
    }
}
