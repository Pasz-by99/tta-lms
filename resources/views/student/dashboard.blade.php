@extends('layouts.app')

@section('title', 'My Learning')

@section('content')
<div class="bg-tta text-white">
    <div class="max-w-6xl mx-auto px-4 py-10">
        <h1 class="text-3xl font-bold">Welcome, {{ auth()->user()->name }}</h1>
        <p class="text-green-100 mt-1">Your learning dashboard</p>

        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('student.calendars') }}"
               class="bg-white text-tta px-4 py-2 rounded-lg font-semibold text-sm">
                Farm Calendars
            </a>
            <a href="{{ route('courses.index') }}"
               class="bg-white/20 text-white px-4 py-2 rounded-lg font-semibold text-sm">
                Browse Courses
            </a>
        </div>
    </div>
</div>

<div class="max-w-6xl mx-auto px-4 py-10">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold">My Enrollments</h2>
        <a href="{{ route('courses.index') }}" class="text-tta text-sm font-medium hover:underline">
            Browse more courses →
        </a>
    </div>

    @if($enrollments->isEmpty())
        <div class="bg-white border rounded-xl p-10 text-center">
            <p class="text-gray-500 mb-4">You are not enrolled in any course yet.</p>
            <a href="{{ route('courses.index') }}"
               class="inline-block bg-tta text-white px-5 py-2 rounded-lg font-semibold">
                Browse Courses
            </a>
        </div>
    @else
        <div class="grid md:grid-cols-2 gap-4">
            @foreach($enrollments as $enrollment)
                @if($enrollment->course)
                    <a href="{{ route('student.course', $enrollment->course->slug) }}"
                       class="block bg-white border rounded-xl p-5 hover:border-green-600 transition">
                        <div class="text-sm text-gray-500 mb-1">
                            {{ $enrollment->course->category->name ?? 'Course' }}
                        </div>
                        <div class="font-bold text-lg text-gray-900 mb-2">
                            {{ $enrollment->course->title }}
                        </div>
                        <div class="text-sm text-gray-600 capitalize">
                            Status: {{ $enrollment->status ?? 'active' }}
                        </div>
                        <div class="mt-3 text-tta text-sm font-semibold">
                            Continue learning →
                        </div>
                    </a>
                @endif
            @endforeach
        </div>
    @endif
</div>
@endsection
