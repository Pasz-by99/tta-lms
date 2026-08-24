<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CalendarEventResource\Pages;
use App\Models\CalendarEvent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CalendarEventResource extends Resource
{
    protected static ?string $model = CalendarEvent::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Farm Tools';

    protected static ?string $navigationLabel = 'Calendar Events';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('calendar_template_id')
                    ->relationship('template', 'title')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label('Calendar Template'),

                Forms\Components\TextInput::make('title')
                    ->required(),

                Forms\Components\TextInput::make('timing')
                    ->placeholder('e.g. At 3 months, Every 6 months, November')
                    ->required(),

                Forms\Components\Select::make('category')
                    ->options([
                        'Vaccination' => 'Vaccination',
                        'Deworming' => 'Deworming',
                        'Breeding' => 'Breeding',
                        'Feeding' => 'Feeding',
                        'Health' => 'Health Check',
                        'Management' => 'Management',
                        'Planting' => 'Planting',
                        'Harvesting' => 'Harvesting',
                        'Other' => 'Other',
                    ]),

                Forms\Components\Textarea::make('description')
                    ->rows(3)
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('template.title')
                    ->label('Template')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('title')
                    ->searchable(),

                Tables\Columns\TextColumn::make('timing'),

                Tables\Columns\BadgeColumn::make('category'),

                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('template')
                    ->relationship('template', 'title'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCalendarEvents::route('/'),
            'create' => Pages\CreateCalendarEvent::route('/create'),
            'edit' => Pages\EditCalendarEvent::route('/{record}/edit'),
        ];
    }
}