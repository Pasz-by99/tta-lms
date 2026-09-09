@extends('layouts.app')

@section('title', $course->title)

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="bg-tta text-white rounded-2xl p-6 mb-8">
        <div class="text-sm text-green-100 mb-1">{{ $course->category->name ?? 'Course' }}</div>
        <h1 class="text-3xl font-bold mb-2">{{ $course->title }}</h1>
        <p class="text-green-50 mb-4">{{ $course->short_description }}</p>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('student.quizzes', $course->slug) }}"
               class="bg-white text-tta px-4 py-2 rounded-lg text-sm font-semibold">
                Quizzes & Tests
            </a>
            <a href="{{ route('student.dashboard') }}"
               class="bg-white/20 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                My Learning
            </a>
        </div>
    </div>

    <h2 class="text-xl font-bold mb-4">Lessons</h2>

    @if($lessons->isEmpty())
        <div class="bg-white border rounded-xl p-8 text-center text-gray-500">
            No published lessons yet.
        </div>
    @else
        <div class="space-y-3">
            @foreach($lessons as $index => $lesson)
                @php
                    $done = isset($progressItems[$lesson->id]) && ($progressItems[$lesson->id]->is_completed ?? false);
                @endphp
                <a href="{{ route('student.lesson', [$course->slug, $lesson->slug]) }}"
                   class="block bg-white border rounded-xl p-4 hover:border-green-600 transition">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-green-100 text-tta flex items-center justify-center font-bold text-sm">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <div class="font-semibold">{{ $lesson->title }}</div>
                                <div class="text-sm text-gray-500 capitalize">
                                    {{ $lesson->content_type ?? 'lesson' }}
                                    @if(!empty($lesson->duration_minutes))
                                        · {{ $lesson->duration_minutes }} min
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="text-sm">
                            @if($done)
                                <span class="text-green-700 font-medium">Completed</span>
                            @else
                                <span class="text-tta font-medium">Open →</span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
