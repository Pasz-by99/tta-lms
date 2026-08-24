<?php

namespace App\Filament\Resources\CalendarTemplateResource\Pages;

use App\Filament\Resources\CalendarTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCalendarTemplates extends ListRecords
{
    protected static string $resource = CalendarTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
