<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class VehicleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('type')
                    ->required(),
                TextInput::make('seats')
                    ->required()
                    ->numeric()
                    ->default(4),
                TextInput::make('plate'),
               
                Toggle::make('is_active')
                    ->required(),
                Textarea::make('notes')
                    ->columnSpanFull(),
                    Select::make('owner_driver_id')
    ->label('Owner (driver)')
    ->relationship('ownerDriver', 'name')   // from Vehicle model
    ->searchable()
    ->preload()
    ->nullable(),
TextInput::make('type')->datalist(['sedan','van','minibus','suv'])->required(),
TextInput::make('seats')->numeric()->minValue(1)->maxValue(50)->default(4),
            ]);
    }
}
