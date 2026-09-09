@extends('layouts.app')

@section('title', $lesson->title)

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="mb-4">
        <a href="{{ route('student.course', $course->slug) }}" class="text-tta hover:underline text-sm">
            ← Back to {{ $course->title }}
        </a>
    </div>

    <div class="bg-white rounded-xl border shadow-sm p-6 mb-6">
        <div class="text-sm text-gray-500 mb-1">{{ $course->title }}</div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $lesson->title }}</h1>
        <div class="flex flex-wrap gap-2 text-sm text-gray-600">
            <span class="bg-gray-100 px-3 py-1 rounded-full capitalize">{{ $lesson->content_type ?? 'lesson' }}</span>
            @if(!empty($lesson->duration_minutes))
                <span class="bg-gray-100 px-3 py-1 rounded-full">{{ $lesson->duration_minutes }} min</span>
            @endif
            @if($lesson->unit)
                <span class="bg-green-100 text-tta px-3 py-1 rounded-full">{{ $lesson->unit->title }}</span>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl border shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Lesson Notes</h2>

        @if(!empty($lesson->content))
            <div class="prose max-w-none text-gray-800 mb-6">
                {!! $lesson->content !!}
            </div>
        @else
            <p class="text-gray-500 mb-4">No written notes for this lesson yet.</p>
        @endif

        @php
            $file = $lesson->file_path
                ?? $lesson->attachment
                ?? $lesson->file
                ?? $lesson->document
                ?? $lesson->notes_file
                ?? null;
        @endphp

        @if(!empty($file))
            <div class="border rounded-lg p-4 bg-green-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="font-medium text-gray-900">Downloadable notes / file</div>
                    <div class="text-sm text-gray-600">{{ basename($file) }}</div>
                </div>
                <a href="{{ asset('storage/' . ltrim(str_replace('public/', '', $file), '/')) }}"
                   target="_blank"
                   class="inline-block text-center bg-tta text-white px-4 py-2 rounded-lg font-medium hover:opacity-90">
                    Download
                </a>
            </div>
        @endif

        @if(!empty($lesson->video_url))
            <div class="mt-6">
                <h3 class="font-semibold mb-2">Video</h3>
                <div class="aspect-video rounded-lg overflow-hidden bg-black">
                    <iframe class="w-full h-full" src="{{ $lesson->video_url }}" allowfullscreen></iframe>
                </div>
            </div>
        @endif
    </div>

    {{-- Mark complete --}}
    <div class="bg-white rounded-xl border shadow-sm p-6 mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            @if(!empty($progress) && ($progress->is_completed ?? false))
                <span class="text-green-700 font-medium">✓ Lesson completed</span>
            @else
                <span class="text-gray-600">Mark this lesson when you finish</span>
            @endif
        </div>

        @if(empty($progress) || !($progress->is_completed ?? false))
            <form method="POST" action="{{ route('student.lesson.complete', [$course->slug, $lesson->slug]) }}">
                @csrf
                <button type="submit" class="bg-tta text-white px-5 py-2 rounded-lg font-semibold">
                    Mark as Completed
                </button>
            </form>
        @endif
    </div>

    {{-- Real next / previous item names --}}
    <div class="grid sm:grid-cols-2 gap-3">
        @if(!empty($previous))
            @php
                $prevUrl = $previous['type'] === 'lesson'
                    ? route('student.lesson', [$course->slug, $previous['slug']])
                    : route('student.quiz.show', [$course->slug, $previous['slug']]);
                $prevType = $previous['type'] === 'lesson' ? 'Lesson' : 'Quiz';
            @endphp
            <a href="{{ $prevUrl }}"
               class="block border rounded-xl px-4 py-4 bg-white hover:border-green-600 hover:bg-green-50 transition">
                <div class="text-xs text-gray-500 mb-1">← Previous {{ $prevType }}</div>
                <div class="font-bold text-gray-900 text-base">{{ $previous['title'] }}</div>
            </a>
        @else
            <div class="border rounded-xl px-4 py-4 bg-gray-50 text-gray-400 text-sm">
                No previous item
            </div>
        @endif

        @if(!empty($next))
            @php
                $nextUrl = $next['type'] === 'lesson'
                    ? route('student.lesson', [$course->slug, $next['slug']])
                    : route('student.quiz.show', [$course->slug, $next['slug']]);
                $nextType = $next['type'] === 'lesson' ? 'Lesson' : 'Quiz';
            @endphp
            <a href="{{ $nextUrl }}"
               class="block border rounded-xl px-4 py-4 bg-white hover:border-green-600 hover:bg-green-50 transition sm:text-right">
                <div class="text-xs text-gray-500 mb-1">Next {{ $nextType }} →</div>
                <div class="font-bold text-gray-900 text-base">{{ $next['title'] }}</div>
            </a>
        @else
            <div class="border rounded-xl px-4 py-4 bg-gray-50 text-gray-400 text-sm sm:text-right">
                End of course content
            </div>
        @endif
    </div>
</div>
@endsection
