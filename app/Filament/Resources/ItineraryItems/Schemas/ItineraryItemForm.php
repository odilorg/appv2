<?php

namespace App\Filament\Resources\ItineraryItems\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Select;

use Filament\Schemas\Schema;

class ItineraryItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tour_id')
                    ->relationship('tour', 'title')
                    ->required(),
                TextInput::make('day_number')
                    ->numeric(),
                TextInput::make('position')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TimePicker::make('start_time'),
                TimePicker::make('end_time'),
                TextInput::make('location'),
            ]);
    }
}
