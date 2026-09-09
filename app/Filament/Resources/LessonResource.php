<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LessonResource\Pages;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Unit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class LessonResource extends Resource
{
    protected static ?string $model = Lesson::class;

    protected static ?string $navigationIcon = 'heroicon-o-play-circle';
    protected static ?string $navigationGroup = 'Learning';
    protected static ?string $navigationLabel = 'Lessons';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Lesson details')->schema([
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
                    ->helperText('Select the unit this lesson belongs to'),

                Forms\Components\TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),

                Forms\Components\Select::make('content_type')
                    ->options([
                        'text' => 'Text / Notes',
                        'video' => 'Video',
                        'file' => 'File',
                        'mixed' => 'Notes + File',
                    ])
                    ->default('mixed')
                    ->required(),

                Forms\Components\TextInput::make('duration_minutes')
                    ->numeric()
                    ->label('Duration (minutes)'),

                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),

                Forms\Components\Toggle::make('is_published')
                    ->label('Published')
                    ->default(true),
            ])->columns(2),

            Forms\Components\Section::make('Notes (type here)')->schema([
                Forms\Components\RichEditor::make('content')
                    ->label('Lesson notes')
                    ->columnSpanFull()
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'underline',
                        'bulletList',
                        'orderedList',
                        'h2',
                        'h3',
                        'link',
                        'undo',
                        'redo',
                    ])
                    ->helperText('You can bold titles, make lists, and format notes.'),
            ]),

            Forms\Components\Section::make('Upload file (optional)')->schema([
                Forms\Components\FileUpload::make('file_path')
                    ->label('PDF / Word / Image')
                    ->disk('public')
                    ->directory('lessons')
                    ->visibility('public')
                    ->downloadable()
                    ->openable()
                    ->preserveFilenames()
                    ->maxSize(20480)
                    ->helperText('Students will get a Download button for this file.'),
            ]),

            Forms\Components\Section::make('Video (optional)')->schema([
                Forms\Components\TextInput::make('video_url')
                    ->label('Video URL')
                    ->url()
                    ->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('course.title')->label('Course')->searchable(),
                Tables\Columns\TextColumn::make('unit.title')->label('Unit')->searchable(),
                Tables\Columns\TextColumn::make('content_type')->badge(),
                Tables\Columns\IconColumn::make('is_published')->boolean()->label('Published'),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
            ])
            ->defaultSort('sort_order')
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLessons::route('/'),
            'create' => Pages\CreateLesson::route('/create'),
            'edit' => Pages\EditLesson::route('/{record}/edit'),
        ];
    }
}
