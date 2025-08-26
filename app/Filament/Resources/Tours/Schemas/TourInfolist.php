<?php

namespace App\Filament\Resources\Tours\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TourInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
                TextEntry::make('title'),
                TextEntry::make('tour_duration')
                    ->numeric(),
            ]);
    }
}
