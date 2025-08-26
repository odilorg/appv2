<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Customer')
                ->columns(2)
                ->schema([
                    TextInput::make('name')->required()->maxLength(255),
                    Select::make('type')->options([
                        'individual' => 'Individual',
                        'company'    => 'Company',
                        'agency'     => 'Agency',
                    ])->default('individual'),

                    TextInput::make('email')->email(),
                    TextInput::make('phone')->tel(),

                    TextInput::make('country_code')->label('Country')->maxLength(2)->placeholder('UZ'),
                    TextInput::make('city'),

                    TextInput::make('preferred_language')->placeholder('en'),
                    Select::make('source')->options([
                        'direct'   => 'Direct',
                        'website'  => 'Website',
                        'instagram'=> 'Instagram',
                        'referral' => 'Referral',
                        'agency'   => 'Agency',
                        'gyg'      => 'GetYourGuide',
                        'viator'   => 'Viator',
                    ])->searchable(),

                    Toggle::make('marketing_opt_in')->label('Marketing opt-in')->default(false),
                    Textarea::make('notes')->columnSpanFull()->rows(4),
                ]),
            ]);
    }
}
