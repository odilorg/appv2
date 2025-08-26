<?php

namespace App\Filament\Resources\Guides\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class GuideForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                 Section::make('Guide')
                ->schema([
                    TextInput::make('name')->required()->maxLength(255),
                    TextInput::make('base_city')->label('Base city')->maxLength(255),
                    TagsInput::make('languages')->label('Languages')->placeholder('Add language'),
                    TextInput::make('phone')->tel(),
                    TextInput::make('email')->email(),
                    Toggle::make('is_active')->label('Active')->default(true),
                   Textarea::make('notes')->columnSpanFull(),
                ])->columns(2),
            ]);
    }
}
