<?php

namespace App\Filament\Resources;

use App\Models\Machine;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MachineResource extends Resource
{
    protected static ?string $model = Machine::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static ?string $navigationGroup = 'Equipamentos';

    protected static ?string $label = 'Máquinas';

    protected static ?string $pluralLabel = 'Máquinas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações Básicas')
                    ->schema([
                        Forms\Components\TextInput::make('serial_number')
                            ->label('Número de Série')
                            ->required()
                            ->unique(ignorable: fn ($record) => $record),
                        Forms\Components\TextInput::make('name')
                            ->label('Nome')
                            ->required(),
                        Forms\Components\TextInput::make('model')
                            ->label('Modelo')
                            ->required(),
                        Forms\Components\TextInput::make('location')
                            ->label('Localização')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Status e Datas')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'operational' => 'Operacional',
                                'maintenance' => 'Em Manutenção',
                                'critical' => 'Crítica',
                                'offline' => 'Offline',
                            ])
                            ->required()
                            ->default('operational'),
                        Forms\Components\DatePicker::make('installed_at')
                            ->label('Data de Instalação')
                            ->required(),
                        Forms\Components\DateTimePicker::make('last_reading_at')
                            ->label('Última Leitura'),
                    ])->columns(2),

                Forms\Components\Section::make('Detalhes')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Descrição')
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('image')
                            ->label('Imagem')
                            ->image()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('serial_number')
                    ->label('Número de Série')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('model')
                    ->label('Modelo'),
                Tables\Columns\TextColumn::make('location')
                    ->label('Localização'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(fn ($record) => match ($record->status) {
                        'operational' => 'Operacional',
                        'maintenance' => 'Em Manutenção',
                        'critical' => 'Crítica',
                        'offline' => 'Offline',
                        default => $record->status,
                    })
                    ->color(fn ($state) => match ($state) {
                        'Operacional' => 'success',
                        'Em Manutenção' => 'warning',
                        'Crítica' => 'danger',
                        'Offline' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('installed_at')
                    ->label('Data de Instalação')
                    ->date('d/m/Y'),
                Tables\Columns\TextColumn::make('last_reading_at')
                    ->label('Última Leitura')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('–'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'operational' => 'Operacional',
                        'maintenance' => 'Em Manutenção',
                        'critical' => 'Crítica',
                        'offline' => 'Offline',
                    ]),
                Tables\Filters\SelectFilter::make('location')
                    ->label('Localização'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Visualizar'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('mark_critical')
                    ->label('Marcar Crítica')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->color('danger')
                    ->action(function (Machine $record) {
                        $record->update(['status' => 'critical']);
                    })
                    ->requiresConfirmation(),
                Tables\Actions\Action::make('mark_operational')
                    ->label('Marcar Operacional')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function (Machine $record) {
                        $record->update(['status' => 'operational']);
                    })
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\MachineResource\Pages\ListMachines::route('/'),
            'create' => \App\Filament\Resources\MachineResource\Pages\CreateMachine::route('/create'),
            'view' => \App\Filament\Resources\MachineResource\Pages\ViewMachine::route('/{record}'),
            'edit' => \App\Filament\Resources\MachineResource\Pages\EditMachine::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'gerente']);
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'gerente']);
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->hasRole('admin');
    }
}
