<?php

namespace App\Filament\Resources\Machines\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class MachineForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('serial_number')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('model')
                    ->required(),
                TextInput::make('location')
                    ->required(),
                Select::make('status')
                    ->options([
            'operational' => 'Operational',
            'maintenance' => 'Maintenance',
            'critical' => 'Critical',
            'offline' => 'Offline',
        ])
                    ->default('operational')
                    ->required(),
                DatePicker::make('installed_at')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->image(),
                DateTimePicker::make('last_reading_at'),
            ]);
    }
}
