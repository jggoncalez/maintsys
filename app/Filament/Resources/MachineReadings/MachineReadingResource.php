<?php

namespace App\Filament\Resources\MachineReadings;

use App\Filament\Resources\MachineReadings\Pages\CreateMachineReading;
use App\Filament\Resources\MachineReadings\Pages\EditMachineReading;
use App\Filament\Resources\MachineReadings\Pages\ListMachineReadings;
use App\Filament\Resources\MachineReadings\Schemas\MachineReadingForm;
use App\Filament\Resources\MachineReadings\Tables\MachineReadingsTable;
use App\Models\MachineReading;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MachineReadingResource extends Resource
{
    protected static ?string $model = MachineReading::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return MachineReadingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MachineReadingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMachineReadings::route('/'),
            'create' => CreateMachineReading::route('/create'),
            'edit' => EditMachineReading::route('/{record}/edit'),
        ];
    }
}
