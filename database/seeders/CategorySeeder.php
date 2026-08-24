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
            [
                'name' => 'Livestock Production & Management',
                'description' => 'Training on cattle, goats, sheep and poultry management, breeding, feeding and health.',
                'sort_order' => 1,
            ],
            [
                'name' => 'Crop Production & Agronomy',
                'description' => 'Modern crop farming techniques, soil management, irrigation and pest control.',
                'sort_order' => 2,
            ],
            [
                'name' => 'Agribusiness & Entrepreneurship',
                'description' => 'Business planning, marketing, financial management and value addition in agriculture.',
                'sort_order' => 3,
            ],
            [
                'name' => 'Animal Health & Veterinary Basics',
                'description' => 'Disease prevention, basic veterinary care and biosecurity for livestock.',
                'sort_order' => 4,
            ],
            [
                'name' => 'Climate-Smart Agriculture',
                'description' => 'Sustainable and climate-resilient farming practices for Namibia.',
                'sort_order' => 5,
            ],
            [
                'name' => 'Farm Machinery & Technology',
                'description' => 'Operation and maintenance of farm equipment and modern agricultural technology.',
                'sort_order' => 6,
            ],
            [
                'name' => 'Youth & Women in Agriculture',
                'description' => 'Empowerment programs and skills development for youth and women in agribusiness.',
                'sort_order' => 7,
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'sort_order' => $category['sort_order'],
                'is_active' => true,
            ]);
        }
    }
}