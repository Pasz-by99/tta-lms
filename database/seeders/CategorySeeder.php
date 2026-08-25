<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Training & Capacity Building', 'description' => 'Professional training and farmer capacity development.', 'sort_order' => 1],
            ['name' => 'Farm Management Consultancy', 'description' => 'Business, planning, budgeting and farm management support.', 'sort_order' => 2],
            ['name' => 'Management Calendars & Planning', 'description' => 'Crop, livestock and seasonal management calendars.', 'sort_order' => 3],
            ['name' => 'Record Keeping & Data Management', 'description' => 'Farm records, reporting and data systems.', 'sort_order' => 4],
            ['name' => 'Livestock & Crop Production Services', 'description' => 'Practical production training for crops and livestock.', 'sort_order' => 5],
            ['name' => 'Feed & Nutrition Analysis', 'description' => 'Animal nutrition, feeding and feed quality.', 'sort_order' => 6],
            ['name' => 'Input Supply & Farm Materials', 'description' => 'Guidance on farm inputs and materials management.', 'sort_order' => 7],
        ];

        foreach ($categories as $item) {
            Category::updateOrCreate(
                ['slug' => Str::slug($item['name'])],
                [
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'sort_order' => $item['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}