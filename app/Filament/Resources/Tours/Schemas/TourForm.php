<?php

namespace App\Filament\Resources\Tours\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TourForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('tour_duration')
                    ->required()
                    ->numeric(),
                Textarea::make('tour_description')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
