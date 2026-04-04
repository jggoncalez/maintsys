<?php

namespace App\Filament\Resources\ServiceOrders\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ServiceOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('machine_id')
                    ->relationship('machine', 'name')
                    ->required(),
                Select::make('technician_id')
                    ->relationship('technician', 'name')
                    ->required(),
                TextInput::make('created_by')
                    ->required()
                    ->numeric(),
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Select::make('type')
                    ->options(['preventive' => 'Preventive', 'corrective' => 'Corrective'])
                    ->required(),
                Select::make('priority')
                    ->options(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'critical' => 'Critical'])
                    ->default('medium')
                    ->required(),
                Select::make('status')
                    ->options([
            'open' => 'Open',
            'in_progress' => 'In progress',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ])
                    ->default('open')
                    ->required(),
                DateTimePicker::make('started_at'),
                DateTimePicker::make('completed_at'),
                Textarea::make('resolution_notes')
                    ->columnSpanFull(),
            ]);
    }
}
