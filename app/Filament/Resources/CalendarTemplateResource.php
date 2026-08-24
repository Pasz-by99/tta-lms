<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CalendarTemplateResource\Pages;
use App\Models\CalendarTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CalendarTemplateResource extends Resource
{
    protected static ?string $model = CalendarTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Farm Tools';

    protected static ?string $navigationLabel = 'Calendar Templates';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) =>
                                $operation === 'create' ? $set('slug', Str::slug($state)) : null
                            ),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true),

                        Forms\Components\Select::make('type')
                            ->options([
                                'livestock' => 'Livestock',
                                'cattle' => 'Cattle',
                                'goat' => 'Goat',
                                'sheep' => 'Sheep',
                                'poultry' => 'Poultry',
                                'crop' => 'Crop Production',
                                'other' => 'Other',
                            ])
                            ->required()
                            ->default('livestock'),

                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),

                        Forms\Components\Toggle::make('is_published')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'success' => 'livestock',
                        'warning' => 'crop',
                        'primary' => 'cattle',
                        'info' => 'goat',
                    ]),

                Tables\Columns\TextColumn::make('events_count')
                    ->counts('events')
                    ->label('Events'),

                Tables\Columns\IconColumn::make('is_published')
                    ->boolean(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCalendarTemplates::route('/'),
            'create' => Pages\CreateCalendarTemplate::route('/create'),
            'edit' => Pages\EditCalendarTemplate::route('/{record}/edit'),
        ];
    }
}