<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            // Livestock
            ['category' => 'Livestock Production & Management', 'title' => 'Cattle Farming Essentials', 'duration' => '5 days', 'price' => 1500, 'level' => 'Beginner'],
            ['category' => 'Livestock Production & Management', 'title' => 'Goat & Sheep Production', 'duration' => '3 days', 'price' => 1200, 'level' => 'Beginner'],
            ['category' => 'Livestock Production & Management', 'title' => 'Poultry Management', 'duration' => '4 days', 'price' => 1100, 'level' => 'Intermediate'],

            // Crop Production
            ['category' => 'Crop Production & Agronomy', 'title' => 'Mahangu & Maize Production', 'duration' => '4 days', 'price' => 1300, 'level' => 'Beginner'],
            ['category' => 'Crop Production & Agronomy', 'title' => 'Vegetable Production under Irrigation', 'duration' => '5 days', 'price' => 1600, 'level' => 'Intermediate'],

            // Agribusiness
            ['category' => 'Agribusiness & Entrepreneurship', 'title' => 'Starting an Agribusiness', 'duration' => '3 days', 'price' => 1400, 'level' => 'Beginner'],
            ['category' => 'Agribusiness & Entrepreneurship', 'title' => 'Agricultural Marketing & Value Addition', 'duration' => '4 days', 'price' => 1500, 'level' => 'Intermediate'],

            // Animal Health
            ['category' => 'Animal Health & Veterinary Basics', 'title' => 'Livestock Disease Prevention', 'duration' => '3 days', 'price' => 1250, 'level' => 'Beginner'],

            // Climate Smart
            ['category' => 'Climate-Smart Agriculture', 'title' => 'Climate-Smart Farming Practices', 'duration' => '4 days', 'price' => 1450, 'level' => 'Intermediate'],

            // Farm Machinery
            ['category' => 'Farm Machinery & Technology', 'title' => 'Basic Farm Machinery Operation', 'duration' => '3 days', 'price' => 1350, 'level' => 'Beginner'],

            // Youth & Women
            ['category' => 'Youth & Women in Agriculture', 'title' => 'Youth Empowerment in Agriculture', 'duration' => '5 days', 'price' => 1000, 'level' => 'Beginner'],
        ];

        foreach ($courses as $item) {
            $category = Category::where('name', $item['category'])->first();

            if ($category) {
                Course::create([
                    'category_id' => $category->id,
                    'title' => $item['title'],
                    'slug' => Str::slug($item['title']),
                    'short_description' => 'Practical training course offered by Tinahls Triad Agro.',
                    'description' => 'This course is designed to equip participants with practical skills and knowledge in ' . strtolower($item['title']) . '.',
                    'price' => $item['price'],
                    'duration' => $item['duration'],
                    'level' => $item['level'],
                    'is_published' => true,
                    'sort_order' => 0,
                ]);
            }
        }
    }
}