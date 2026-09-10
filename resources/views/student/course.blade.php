@extends('layouts.app')

@section('title', $course->title)

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="bg-tta text-white rounded-2xl p-6 mb-8">
        <div class="text-sm text-green-100 mb-1">{{ $course->category->name ?? 'Course' }}</div>
        <h1 class="text-3xl font-bold mb-2">{{ $course->title }}</h1>
        <p class="text-green-50 mb-4">{{ $course->short_description }}</p>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('student.grades', $course->slug) }}"
               class="bg-white text-tta px-4 py-2 rounded-lg text-sm font-semibold">
                Grades
            </a>
            <a href="{{ route('student.dashboard') }}"
               class="bg-white/20 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                My Learning
            </a>
        </div>
    </div>

    @if($units->isEmpty())
        <div class="bg-white border rounded-xl p-8 text-center text-gray-500">
            No units published yet for this course.
        </div>
    @else
        <div class="space-y-4">
            @foreach($units as $unit)
                <div class="bg-white border rounded-xl overflow-hidden">
                    <div class="bg-gray-50 px-5 py-4 border-b">
                        <h2 class="font-bold text-lg text-gray-900">{{ $unit->title }}</h2>
                        @if($unit->description)
                            <p class="text-sm text-gray-600 mt-1">{{ $unit->description }}</p>
                        @endif
                    </div>

                    <div class="divide-y">
                        @forelse($unit->lessons as $lesson)
                            @php
                                $done = isset($progressItems[$lesson->id]) && ($progressItems[$lesson->id]->is_completed ?? false);
                            @endphp
                            <a href="{{ route('student.lesson', [$course->slug, $lesson->slug]) }}"
                               class="flex items-center justify-between gap-3 px-5 py-4 hover:bg-green-50 transition">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-green-100 text-tta flex items-center justify-center text-sm font-bold">L</div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $lesson->title }}</div>
                                        <div class="text-xs text-gray-500">Lesson</div>
                                    </div>
                                </div>
                                <div class="text-sm">
                                    @if($done)
                                        <span class="text-green-700 font-medium">Done</span>
                                    @else
                                        <span class="text-tta font-medium">Open →</span>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <div class="px-5 py-3 text-sm text-gray-400">No lessons in this unit yet.</div>
                        @endforelse

                        @foreach($unit->quizzes as $quiz)
                            <a href="{{ route('student.quiz.show', [$course->slug, $quiz->slug]) }}"
                               class="flex items-center justify-between gap-3 px-5 py-4 hover:bg-green-50 transition">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center text-sm font-bold">Q</div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $quiz->title }}</div>
                                        <div class="text-xs text-gray-500 capitalize">{{ $quiz->type }} · Pass {{ $quiz->pass_percentage }}%</div>
                                    </div>
                                </div>
                                <span class="text-tta text-sm font-medium">Start →</span>
                            </a>
                        @endforeach

                        @foreach($unit->assignments as $assignment)
                            <a href="{{ route('student.assignment.show', [$course->slug, $assignment->slug]) }}"
                               class="flex items-center justify-between gap-3 px-5 py-4 hover:bg-green-50 transition">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-sm font-bold">A</div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $assignment->title }}</div>
                                        <div class="text-xs text-gray-500">Assignment · Pass {{ $assignment->pass_score }}/{{ $assignment->max_score }}</div>
                                    </div>
                                </div>
                                <span class="text-tta text-sm font-medium">Open →</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if($orphanLessons->isNotEmpty())
        <div class="mt-6 bg-white border rounded-xl overflow-hidden">
            <div class="bg-gray-50 px-5 py-4 border-b font-bold">Other lessons</div>
            <div class="divide-y">
                @foreach($orphanLessons as $lesson)
                    <a href="{{ route('student.lesson', [$course->slug, $lesson->slug]) }}"
                       class="block px-5 py-4 hover:bg-green-50">
                        {{ $lesson->title }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
