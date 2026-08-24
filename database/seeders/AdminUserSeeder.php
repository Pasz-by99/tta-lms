<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@tta.com'],
            [
                'name' => 'TTA Admin',
                'password' => Hash::make('Admin@123'),
                'role' => 'admin',
                'must_change_password' => false,
                'student_number' => null,
            ]
        );
    }
}