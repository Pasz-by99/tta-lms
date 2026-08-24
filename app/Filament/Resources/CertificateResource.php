<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CertificateResource\Pages;
use App\Models\Certificate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CertificateResource extends Resource
{
    protected static ?string $model = Certificate::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';

    protected static ?string $navigationGroup = 'Students';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Certificate Details')
                    ->schema([
                        Forms\Components\TextInput::make('certificate_number')
                            ->default(fn () => 'TTA-' . strtoupper(Str::random(8)))
                            ->required()
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('student_name')
                            ->required(),

                        Forms\Components\TextInput::make('student_phone')
                            ->tel(),

                        Forms\Components\Select::make('course_id')
                            ->relationship('course', 'title')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Forms\Components\DatePicker::make('issued_date')
                            ->required()
                            ->default(now()),

                        Forms\Components\TextInput::make('issued_by')
                            ->default('Tinahls Triad Agro')
                            ->required(),

                        Forms\Components\Textarea::make('notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('certificate_number')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('student_name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('course.title')
                    ->label('Course')
                    ->limit(30),

                Tables\Columns\TextColumn::make('issued_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('issued_date', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('print')
                    ->label('View Certificate')
                    ->icon('heroicon-o-printer')
                    ->url(fn (Certificate $record) => route('certificate.show', $record))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListCertificates::route('/'),
            'create' => Pages\CreateCertificate::route('/create'),
            'edit' => Pages\EditCertificate::route('/{record}/edit'),
        ];
    }
}