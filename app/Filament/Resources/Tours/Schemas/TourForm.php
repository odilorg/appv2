<?php

namespace App\Filament\Resources\Tours\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;

class TourForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('Tour editor')
                ->persistTabInQueryString()
                ->tabs([
                    Tab::make('Basics')
                        ->schema([
                            Section::make()
                                ->schema([
                                    TextInput::make('title')
                                        ->label('Title')
                                        ->placeholder('e.g. Shahrisabz day trip (no guide)')
                                        ->required()
                                        ->maxLength(255)
                                        ->columnSpanFull(),

                                    TextInput::make('tour_duration')
                                        ->label('Tour duration')
                                        ->numeric()
                                        ->minValue(1)
                                        ->required()
                                        ->suffix('day(s)')
                                        ->helperText('Total length of the tour in days.'),

                                    Textarea::make('tour_description')
                                        ->label('Tour description')
                                        ->autosize()
                                        ->rows(6)
                                        ->required()
                                        ->helperText('Short marketing description shown on listings.')
                                        ->columnSpanFull(),
                                ])
                                ->columns(2),
                        ]),

                    Tab::make('Itinerary')
                        ->schema([
                            Section::make('Itinerary items')
                                ->description('Add stops/activities in the order they happen.')
                                ->schema([
                                    Repeater::make('itineraryItems')
    ->relationship()
    ->orderColumn('position')
    ->collapsible()
    ->reorderable()
    ->addActionLabel('Add itinerary item')
   
    ->itemLabel(fn (array $state): string => $state['title'] ?? 'Itinerary item')
    ->columnSpanFull()
    ->schema([
        TextInput::make('day_number')
            ->label('Day #')
            ->numeric()
            ->minValue(1)
            ->placeholder('1')
            ->columnSpan(2),   // small width

        TextInput::make('title')
            ->label('Title')
            ->placeholder('e.g. Konigil Paper Mill')
            ->required()
            ->maxLength(255)
            ->columnSpan(10),  // takes rest of row

        Textarea::make('description')
            ->label('Description')
            ->autosize()
            ->rows(3)
            ->placeholder('What happens here, highlights, notes...')
            ->columnSpanFull(),

        TimePicker::make('start_time')
            ->label('Start time')
            ->seconds(false)
            ->columnSpan(3),

        TimePicker::make('end_time')
            ->label('End time')
            ->seconds(false)
            ->columnSpan(3),

        TextInput::make('location')
            ->label('Location')
            ->placeholder('e.g. Konigil, Samarkand')
            ->maxLength(255)
            ->columnSpan(6),   // now bigger, balanced with times
    ])
    ->columns(12),   // 12-col grid inside repeater
                                ]),
                        ]),
                ]),
        ]);
    }
}
