<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuizResource\Pages;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\Unit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class QuizResource extends Resource
{
    protected static ?string $model = Quiz::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Learning';
    protected static ?string $navigationLabel = 'Quizzes & Tests';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Quiz Details')->schema([
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
                    ->searchable()
                    ->helperText('Optional: attach quiz to a unit'),

                Forms\Components\TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),

                Forms\Components\Select::make('type')
                    ->options([
                        'quiz' => 'Quiz',
                        'test' => 'Test',
                        'activity' => 'Activity',
                    ])
                    ->required()
                    ->default('quiz'),

                Forms\Components\Textarea::make('description')
                    ->rows(3)
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('time_limit_minutes')
                    ->numeric()
                    ->label('Time limit (minutes)'),

                Forms\Components\TextInput::make('pass_percentage')
                    ->numeric()
                    ->default(50)
                    ->suffix('%')
                    ->required(),

                Forms\Components\Toggle::make('is_published')
                    ->label('Published')
                    ->default(false),

                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
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
                Tables\Columns\TextColumn::make('type')->badge(),
                Tables\Columns\TextColumn::make('questions_count')->counts('questions')->label('Questions'),
                Tables\Columns\TextColumn::make('pass_percentage')->suffix('%'),
                Tables\Columns\IconColumn::make('is_published')->boolean()->label('Published'),
            ])
            ->defaultSort('created_at', 'desc')
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
            \App\Filament\Resources\QuizResource\RelationManagers\QuestionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuizzes::route('/'),
            'create' => Pages\CreateQuiz::route('/create'),
            'edit' => Pages\EditQuiz::route('/{record}/edit'),
        ];
    }
}
