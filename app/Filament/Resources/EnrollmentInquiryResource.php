<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EnrollmentInquiryResource\Pages;
use App\Models\EnrollmentInquiry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EnrollmentInquiryResource extends Resource
{
    protected static ?string $model = EnrollmentInquiry::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox';

    protected static ?string $navigationGroup = 'Students';

    protected static ?string $navigationLabel = 'Enrollment Inquiries';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('course_id')
                    ->relationship('course', 'title')
                    ->searchable()
                    ->preload(),

                Forms\Components\TextInput::make('full_name')
                    ->required(),

                Forms\Components\TextInput::make('phone')
                    ->required()
                    ->tel(),

                Forms\Components\TextInput::make('email')
                    ->email(),

                Forms\Components\Textarea::make('message')
                    ->rows(4)
                    ->columnSpanFull(),

                Forms\Components\Select::make('status')
                    ->options([
                        'new' => 'New',
                        'contacted' => 'Contacted',
                        'enrolled' => 'Enrolled',
                        'rejected' => 'Rejected',
                    ])
                    ->required()
                    ->default('new'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),

                Tables\Columns\TextColumn::make('course.title')
                    ->label('Course')
                    ->limit(30),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'primary' => 'new',
                        'warning' => 'contacted',
                        'success' => 'enrolled',
                        'danger' => 'rejected',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'new' => 'New',
                        'contacted' => 'Contacted',
                        'enrolled' => 'Enrolled',
                        'rejected' => 'Rejected',
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
            'index' => Pages\ListEnrollmentInquiries::route('/'),
            'create' => Pages\CreateEnrollmentInquiry::route('/create'),
            'edit' => Pages\EditEnrollmentInquiry::route('/{record}/edit'),
        ];
    }
}