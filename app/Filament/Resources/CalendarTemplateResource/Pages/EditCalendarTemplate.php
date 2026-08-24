<?php

namespace App\Filament\Resources\CalendarTemplateResource\Pages;

use App\Filament\Resources\CalendarTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCalendarTemplate extends EditRecord
{
    protected static string $resource = CalendarTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
