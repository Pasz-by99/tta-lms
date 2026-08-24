<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Certificate;
use App\Models\Lesson;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Students', User::where('role', 'student')->count())
                ->description('Registered students')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Total Courses', Course::count())
                ->description('Published & drafts')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary'),

            Stat::make('Active Enrollments', Enrollment::where('status', 'active')->count())
                ->description('Currently learning')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('warning'),

            Stat::make('Certificates Issued', Certificate::count())
                ->description('Total certificates')
                ->descriptionIcon('heroicon-m-document-check')
                ->color('info'),

            Stat::make('Total Lessons', Lesson::count())
                ->description('Across all courses')
                ->descriptionIcon('heroicon-m-play-circle')
                ->color('gray'),
        ];
    }
}