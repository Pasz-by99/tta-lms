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
            [
                'title' => 'Crop & Livestock Production Training',
                'category' => 'Livestock & Crop Production Services',
                'price' => 350,
                'duration' => 'Self-paced',
                'level' => 'Beginner',
                'short_description' => 'Practical online training covering both crop and livestock production fundamentals.',
            ],
            [
                'title' => 'Farm Business & Financial Management',
                'category' => 'Farm Management Consultancy',
                'price' => 400,
                'duration' => 'Self-paced',
                'level' => 'Intermediate',
                'short_description' => 'Learn how to manage farm finances, cash flow and business decisions.',
            ],
            [
                'title' => 'Sustainable & Climate-Smart Farming',
                'category' => 'Training & Capacity Building',
                'price' => 350,
                'duration' => 'Self-paced',
                'level' => 'Beginner',
                'short_description' => 'Climate-smart practices for resilient and sustainable farming.',
            ],
            [
                'title' => 'Farmer Mentorship & Coaching',
                'category' => 'Training & Capacity Building',
                'price' => 300,
                'duration' => 'Per session',
                'level' => 'Beginner',
                'short_description' => 'Guided mentorship and coaching for practical farm improvement.',
            ],
            [
                'title' => 'Farm Management Planning',
                'category' => 'Farm Management Consultancy',
                'price' => 400,
                'duration' => 'Self-paced',
                'level' => 'Intermediate',
                'short_description' => 'Plan farm operations, resources and production activities effectively.',
            ],
            [
                'title' => 'Profitability Assessment',
                'category' => 'Farm Management Consultancy',
                'price' => 450,
                'duration' => 'Self-paced',
                'level' => 'Intermediate',
                'short_description' => 'Assess farm profitability and identify improvement opportunities.',
            ],
            [
                'title' => 'Resource Optimization',
                'category' => 'Farm Management Consultancy',
                'price' => 350,
                'duration' => 'Self-paced',
                'level' => 'Intermediate',
                'short_description' => 'Make better use of land, labour, capital and farm resources.',
            ],
            [
                'title' => 'Enterprise Budgeting',
                'category' => 'Farm Management Consultancy',
                'price' => 400,
                'duration' => 'Self-paced',
                'level' => 'Intermediate',
                'short_description' => 'Build enterprise budgets for crops and livestock enterprises.',
            ],
            [
                'title' => 'Monitoring & Evaluation',
                'category' => 'Farm Management Consultancy',
                'price' => 350,
                'duration' => 'Self-paced',
                'level' => 'Intermediate',
                'short_description' => 'Track farm performance and evaluate results over time.',
            ],
            [
                'title' => 'Crop Production Calendars',
                'category' => 'Management Calendars & Planning',
                'price' => 300,
                'duration' => 'Self-paced',
                'level' => 'Beginner',
                'short_description' => 'Seasonal crop calendars for better planning and timing.',
            ],
            [
                'title' => 'Livestock Management Calendars',
                'category' => 'Management Calendars & Planning',
                'price' => 300,
                'duration' => 'Self-paced',
                'level' => 'Beginner',
                'short_description' => 'Livestock activity calendars for breeding, health and management.',
            ],
            [
                'title' => 'Seasonal Operation Schedules',
                'category' => 'Management Calendars & Planning',
                'price' => 250,
                'duration' => 'Self-paced',
                'level' => 'Beginner',
                'short_description' => 'Organize seasonal farm operations with clear schedules.',
            ],
            [
                'title' => 'Breeding, Vaccination & Health Calendars',
                'category' => 'Management Calendars & Planning',
                'price' => 350,
                'duration' => 'Self-paced',
                'level' => 'Intermediate',
                'short_description' => 'Health, vaccination and breeding schedules for livestock.',
            ],
            [
                'title' => 'Grazing & Pasture Management Plans',
                'category' => 'Livestock & Crop Production Services',
                'price' => 400,
                'duration' => 'Self-paced',
                'level' => 'Intermediate',
                'short_description' => 'Plan grazing systems and improve pasture productivity.',
            ],
            [
                'title' => 'Farm Record-Keeping Systems',
                'category' => 'Record Keeping & Data Management',
                'price' => 350,
                'duration' => 'Self-paced',
                'level' => 'Beginner',
                'short_description' => 'Set up simple and practical farm record-keeping systems.',
            ],
        ];

        foreach ($courses as $item) {
            $category = Category::where('name', $item['category'])->first();
            if (!$category) {
                continue;
            }

            Course::updateOrCreate(
                ['slug' => Str::slug($item['title'])],
                [
                    'category_id' => $category->id,
                    'title' => $item['title'],
                    'short_description' => $item['short_description'],
                    'description' => $item['short_description'] . "\n\nContact Tinahls Triad Agro to enroll and activate access after payment.",
                    'price' => $item['price'],
                    'duration' => $item['duration'],
                    'level' => $item['level'],
                    'is_published' => true,
                    'sort_order' => 0,
                ]
            );
        }
    }
}