<?php

namespace App\Filament\Resources\Bookings\RelationManagers;

use Carbon\Carbon;
use App\Models\Guide;
use App\Models\Driver;
use App\Models\Vehicle;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Actions\DissociateBulkAction;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Resources\RelationManagers\RelationManager;

class DaysRelationManager extends RelationManager
{
    protected static string $relationship = 'days';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('date')
                    ->required()
                    ->maxLength(255),
                Section::make('Day')
                    ->schema([
                        TextInput::make('day_number')->label('Day #')->disabled(),
                        DatePicker::make('date')->label('Date')->disabled(),
                    ])
                    ->columns(2)           // two fields on one row
                    ->columnSpanFull(),    // stretch section full width

                // (Optional) your Plan/Items section here, also full width
                // Section::make('Plan (items)')->schema([...])->columnSpanFull(),

                Section::make('Assignments (suppliers)')
                    ->schema([
                        Repeater::make('assignments')
                            ->relationship()
                            ->columns(12)
                            ->addActionLabel('Add assignment')

                            // ⬇️ Ensure assignable_type is set BEFORE creating new rows:
                            ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                                $data['assignable_type'] = match ($data['role'] ?? null) {
                                    'guide'   => Guide::class,
                                    'driver'  => Driver::class,
                                    'vehicle' => Vehicle::class,
                                    default   => null,
                                };
                                return $data;
                            })

                            // ⬇️ Also set it BEFORE updating existing rows:
                            ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {
                                if (empty($data['assignable_type'])) {
                                    $data['assignable_type'] = match ($data['role'] ?? null) {
                                        'guide'   => Guide::class,
                                        'driver'  => Driver::class,
                                        'vehicle' => Vehicle::class,
                                        default   => null,
                                    };
                                }
                                return $data;
                            })

                            ->schema([
                                Select::make('role')
                                    ->options([
                                        'guide'   => 'Guide',
                                        'driver'  => 'Driver',
                                        'vehicle' => 'Vehicle',
                                    ])
                                    ->required()
                                    ->live()
                                    ->columnSpan(3),

                                Select::make('assignable_id')
                                    ->label('Supplier')
                                    ->options(fn(Get $get) => match ($get('role')) {
                                        'guide'   => \App\Models\Guide::where('is_active', 1)->pluck('name', 'id'),
                                        'driver'  => \App\Models\Driver::where('is_active', 1)->pluck('name', 'id'),
                                        'vehicle' => \App\Models\Vehicle::where('is_active', 1)->pluck('type', 'id'),
                                        default   => collect(),
                                    })
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(5),

                                TimePicker::make('start_time')->seconds(false)->columnSpan(2),
                                TimePicker::make('end_time')->seconds(false)->columnSpan(2),

                                TextInput::make('price')->numeric()->prefix('Cost')->columnSpan(3),
                                TextInput::make('currency')->maxLength(3)->default('USD')->columnSpan(2),
                                Toggle::make('is_confirmed')->label('Confirmed')->columnSpan(2),

                                Textarea::make('notes')->columnSpanFull(),

                                Hidden::make('assignable_type')
                                    ->dehydrated()
                                    ->default(fn(Get $get) => [
                                        'guide'   => Guide::class,
                                        'driver'  => Driver::class,
                                        'vehicle' => Vehicle::class,
                                    ][$get('role')] ?? null),
                            ]),
                    ])
                    ->columnSpanFull(),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('date')
            ->columns([
                TextColumn::make('date')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add next day')
                    ->modalWidth('full')
                    // v4: using() lets us fully control creation
                    ->using(function (array $data, $livewire) {
                        $booking   = $livewire->getOwnerRecord();

                        // next day number & date from booking start_date
                        $lastDay   = (int) ($booking->days()->max('day_number') ?? 0);
                        $dayNumber = $lastDay + 1;
                        $date      = Carbon::parse($booking->start_date)->addDays($dayNumber - 1)->toDateString();

                        // create the booking day
                        $day = $booking->days()->create([
                            'day_number' => $dayNumber,
                            'date'       => $date,
                        ]);

                        // 🔁 Auto-copy itinerary items from the Tour template
                        $items = $booking->tour->itineraryItems()
                            ->where(function ($q) use ($dayNumber) {
                                $q->whereNull('day_number')->orWhere('day_number', $dayNumber);
                            })
                            ->orderBy('position')
                            ->get();

                        foreach ($items as $idx => $it) {
                            $day->items()->create([
                                'itinerary_item_id' => $it->id,
                                'position'          => $idx + 1,
                                'title'             => $it->title,
                                'description'       => $it->description,
                                'start_time'        => $it->start_time,
                                'end_time'          => $it->end_time,
                                'location'          => $it->location,
                            ]);
                        }

                        return $day; // return the new record so Filament can save nested relationships
                    })
                    ->form([
                        Section::make('Day')
                            ->schema([
                                TextInput::make('day_number')
                                    ->label('Day #')
                                    ->disabled()
                                    ->dehydrated(false),
                                DatePicker::make('date')
                                    ->label('Date')
                                    ->disabled()
                                    ->dehydrated(false),
                            ])
                            ->columns(2)
                            ->columnSpanFull(),

                        Section::make('Assignments (suppliers)')
                            ->schema([
                                Repeater::make('assignments')
                                    ->relationship()
                                    ->columns(12)
                                    ->addActionLabel('Add assignment')

                                    // ensure morph type is always set
                                    ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                                        $data['assignable_type'] = match ($data['role'] ?? null) {
                                            'guide'   => Guide::class,
                                            'driver'  => Driver::class,
                                            'vehicle' => Vehicle::class,
                                            default   => null,
                                        };
                                        return $data;
                                    })
                                    ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {
                                        if (empty($data['assignable_type'])) {
                                            $data['assignable_type'] = match ($data['role'] ?? null) {
                                                'guide'   => Guide::class,
                                                'driver'  => Driver::class,
                                                'vehicle' => Vehicle::class,
                                                default   => null,
                                            };
                                        }
                                        return $data;
                                    })

                                    ->schema([
                                        Select::make('role')
                                            ->options([
                                                'guide'   => 'Guide',
                                                'driver'  => 'Driver',
                                                'vehicle' => 'Vehicle',
                                            ])
                                            ->required()
                                            ->live()
                                            ->columnSpan(3),

                                        Select::make('assignable_id')
                                            ->label('Supplier')
                                            ->options(fn(Get $get) => match ($get('role')) {
                                                'guide'   => Guide::where('is_active', 1)->pluck('name', 'id'),
                                                'driver'  => Driver::where('is_active', 1)->pluck('name', 'id'),
                                                'vehicle' => Vehicle::where('is_active', 1)->pluck('type', 'id'),
                                                default   => collect(),
                                            })
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->columnSpan(5),

                                        TimePicker::make('start_time')->seconds(false)->columnSpan(2),
                                        TimePicker::make('end_time')->seconds(false)->columnSpan(2),

                                        TextInput::make('price')->numeric()->prefix('Cost')->columnSpan(3),
                                        TextInput::make('currency')->maxLength(3)->default('USD')->columnSpan(2),
                                        Toggle::make('is_confirmed')->label('Confirmed')->columnSpan(2),

                                        Textarea::make('notes')->columnSpanFull(),
                                    ]),
                            ])
                            ->columnSpanFull(),
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                //DissociateAction::make(),
              //  DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
