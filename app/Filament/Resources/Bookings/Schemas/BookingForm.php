<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
               Section::make('Booking')
                ->schema([
                    Select::make('tour_id')
                        ->label('Tour')
                        ->relationship('tour', 'title')
                        ->required()
                        ->preload()
                        ->searchable(),

                    Select::make('customer_id')
                        ->label('Customer')
                        ->relationship('customer', 'name')
                        ->preload()
                        ->searchable(),

                    DatePicker::make('start_date')->required(),

                    TextInput::make('pax_adults')->numeric()->minValue(1)->default(2)->label('Adults'),
                    TextInput::make('pax_children')->numeric()->minValue(0)->default(0)->label('Children'),

                    Select::make('status')->options([
                        'draft' => 'Draft',
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                    ])->default('draft'),

                    TextInput::make('selling_price')->numeric()->prefix('Total'),
                    TextInput::make('currency')->default('USD')->maxLength(3)->minLength(3),
                    Textarea::make('notes')->columnSpanFull(),
                ])->columns(2),
            ]);
    }
}
