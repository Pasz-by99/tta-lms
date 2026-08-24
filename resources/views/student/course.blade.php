@extends('layouts.app')

@section('title', $course->title)

@section('content')
<div class="bg-tta text-white py-10">
    <div class="max-w-5xl mx-auto px-4">
        <a href="{{ route('student.dashboard') }}" class="text-green-200 text-sm hover:underline">← Back to Dashboard</a>
        <h1 class="text-3xl font-bold mt-2">{{ $course->title }}</h1>
        <p class="text-green-100 mt-1">
            {{ $course->lessons->count() }} lessons
            @if(count($completedLessons) > 0)
                • {{ count($completedLessons) }} completed
            @endif
        </p>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 py-10">
    <div class="bg-white rounded-xl shadow-sm border">
        <div class="p-6 border-b">
            <h2 class="text-xl font-bold">Course Content</h2>
        </div>

        <div class="divide-y">
            @foreach($course->lessons as $index => $lesson)
                <a href="{{ route('student.lesson', [$course->slug, $lesson->slug]) }}" 
                   class="flex items-center gap-4 p-5 hover:bg-gray-50 transition">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold
                                {{ in_array($lesson->id, $completedLessons) ? 'bg-green-500 text-white' : 'bg-green-100 text-tta' }}">
                        @if(in_array($lesson->id, $completedLessons))
                            ✓
                        @else
                            {{ $index + 1 }}
                        @endif
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold">{{ $lesson->title }}</h3>
                        <div class="text-sm text-gray-500 capitalize">
                            {{ $lesson->content_type }}
                            @if($lesson->duration_minutes)
                                • {{ $lesson->duration_minutes }} min
                            @endif
                        </div>
                    </div>
                    <div class="text-tta">→</div>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endsection