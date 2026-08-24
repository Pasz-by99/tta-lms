<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['role'] = 'student';
        $data['student_number'] = User::generateStudentNumber();
        $data['password'] = Hash::make('Password123');
        $data['must_change_password'] = true;

        if (empty($data['email'])) {
            $data['email'] = strtolower($data['student_number']) . '@tta.local';
        }

        return $data;
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Student created. Default password: Password123';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}