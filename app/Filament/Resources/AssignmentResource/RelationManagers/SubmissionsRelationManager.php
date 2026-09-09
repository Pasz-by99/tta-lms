<?php

namespace App\Filament\Resources\AssignmentResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SubmissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'submissions';

    protected static ?string $title = 'Student Submissions';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('score')
                ->numeric()
                ->required(),

            Forms\Components\Textarea::make('feedback')
                ->rows(4)
                ->columnSpanFull(),

            Forms\Components\Select::make('status')
                ->options([
                    'submitted' => 'Submitted',
                    'graded' => 'Graded',
                ])
                ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Student'),
                Tables\Columns\TextColumn::make('user.student_number')->label('Student No'),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('score'),
                Tables\Columns\TextColumn::make('submitted_at')->dateTime(),
                Tables\Columns\TextColumn::make('graded_at')->dateTime(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Mark')
                    ->mutateFormDataUsing(function (array $data) {
                        $data['status'] = 'graded';
                        $data['graded_at'] = now();
                        return $data;
                    }),
            ]);
    }
}
