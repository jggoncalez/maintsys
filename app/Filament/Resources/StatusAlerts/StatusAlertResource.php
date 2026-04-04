<?php

namespace App\Filament\Resources\StatusAlerts;

use App\Filament\Resources\StatusAlerts\Pages\CreateStatusAlert;
use App\Filament\Resources\StatusAlerts\Pages\EditStatusAlert;
use App\Filament\Resources\StatusAlerts\Pages\ListStatusAlerts;
use App\Filament\Resources\StatusAlerts\Schemas\StatusAlertForm;
use App\Filament\Resources\StatusAlerts\Tables\StatusAlertsTable;
use App\Models\StatusAlert;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StatusAlertResource extends Resource
{
    protected static ?string $model = StatusAlert::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return StatusAlertForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StatusAlertsTable::configure($table);
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
            'index' => ListStatusAlerts::route('/'),
            'create' => CreateStatusAlert::route('/create'),
            'edit' => EditStatusAlert::route('/{record}/edit'),
        ];
    }
}
