<?php

namespace App\Filament\Resources\EnrollmentInquiryResource\Pages;

use App\Filament\Resources\EnrollmentInquiryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEnrollmentInquiries extends ListRecords
{
    protected static string $resource = EnrollmentInquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
