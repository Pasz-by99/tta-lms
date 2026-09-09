<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssignmentResource\Pages;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\Unit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class AssignmentResource extends Resource
{
    protected static ?string $model = Assignment::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Learning';

    protected static ?string $navigationLabel = 'Assignments';

    protected static ?int $navigationSort = 6;

    protected static bool $shouldRegisterNavigation = true;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Assignment')->schema([
                Forms\Components\Select::make('course_id')
                    ->label('Course')
                    ->options(Course::query()->pluck('title', 'id'))
                    ->required()
                    ->searchable()
                    ->live(),

                Forms\Components\Select::make('unit_id')
                    ->label('Unit / Module')
                    ->options(function (Get $get) {
                        $courseId = $get('course_id');
                        if (!$courseId) {
                            return [];
                        }

                        return Unit::where('course_id', $courseId)
                            ->orderBy('sort_order')
                            ->pluck('title', 'id');
                    })
                    ->searchable(),

                Forms\Components\TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

                Forms\Components\TextInput::make('slug')
                    ->required(),

                Forms\Components\RichEditor::make('instructions')
                    ->label('Instructions')
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('attachment')
                    ->label('Assignment file (optional)')
                    ->disk('public')
                    ->directory('assignments')
                    ->visibility('public')
                    ->downloadable()
                    ->openable(),

                Forms\Components\TextInput::make('max_score')
                    ->numeric()
                    ->default(100)
                    ->required(),

                Forms\Components\TextInput::make('pass_score')
                    ->numeric()
                    ->default(50)
                    ->required(),

                Forms\Components\DateTimePicker::make('due_at')
                    ->label('Due date'),

                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),

                Forms\Components\Toggle::make('is_published')
                    ->label('Published')
                    ->default(false),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('course.title')->label('Course'),
                Tables\Columns\TextColumn::make('unit.title')->label('Unit'),
                Tables\Columns\TextColumn::make('max_score'),
                Tables\Columns\TextColumn::make('pass_score'),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
                Tables\Columns\TextColumn::make('due_at')->dateTime(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Submissions relation can be added after menu appears
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssignments::route('/'),
            'create' => Pages\CreateAssignment::route('/create'),
            'edit' => Pages\EditAssignment::route('/{record}/edit'),
        ];
    }
}
