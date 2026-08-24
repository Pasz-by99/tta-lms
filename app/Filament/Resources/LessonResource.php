<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LessonResource\Pages;
use App\Models\Lesson;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class LessonResource extends Resource
{
    protected static ?string $model = Lesson::class;

    protected static ?string $navigationIcon = 'heroicon-o-play-circle';

    protected static ?string $navigationGroup = 'Learning';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Lesson Information')
                    ->schema([
                        Forms\Components\Select::make('course_id')
                            ->relationship('course', 'title')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) =>
                                $operation === 'create' ? $set('slug', Str::slug($state)) : null
                            ),

                        Forms\Components\TextInput::make('slug')
                            ->required(),

                        Forms\Components\Select::make('content_type')
                            ->options([
                                'video' => 'Video (YouTube / Vimeo)',
                                'text'  => 'Notes / Text',
                                'file'  => 'Downloadable File',
                            ])
                            ->required()
                            ->live()
                            ->default('text'),

                        Forms\Components\TextInput::make('duration_minutes')
                            ->numeric()
                            ->label('Duration (minutes)'),

                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),

                        Forms\Components\Toggle::make('is_published')
                            ->default(true),
                    ])->columns(2),

                Forms\Components\Section::make('Content')
                    ->schema([
                        Forms\Components\TextInput::make('video_url')
                            ->label('Video URL (YouTube or Vimeo)')
                            ->visible(fn (Forms\Get $get) => $get('content_type') === 'video')
                            ->url(),

                        Forms\Components\RichEditor::make('content')
                            ->label('Notes / Lesson Content')
                            ->visible(fn (Forms\Get $get) => $get('content_type') === 'text')
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('file_path')
                            ->label('Upload File (PDF, etc.)')
                            ->directory('lesson-files')
                            ->visible(fn (Forms\Get $get) => $get('content_type') === 'file')
                            ->downloadable(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('course.title')
                    ->label('Course')
                    ->sortable()
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('content_type')
                    ->colors([
                        'primary' => 'video',
                        'success' => 'text',
                        'warning' => 'file',
                    ]),

                Tables\Columns\TextColumn::make('duration_minutes')
                    ->label('Minutes')
                    ->sortable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_published')
                    ->boolean(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('course')
                    ->relationship('course', 'title'),

                Tables\Filters\SelectFilter::make('content_type')
                    ->options([
                        'video' => 'Video',
                        'text'  => 'Text',
                        'file'  => 'File',
                    ]),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLessons::route('/'),
            'create' => Pages\CreateLesson::route('/create'),
            'edit' => Pages\EditLesson::route('/{record}/edit'),
        ];
    }
}