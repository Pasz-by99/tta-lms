@extends('layouts.app')

@section('title', $assignment->title)

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <a href="{{ route('student.course', $course->slug) }}" class="text-tta text-sm hover:underline">
        ← Back to {{ $course->title }}
    </a>

    <h1 class="text-2xl font-bold mt-2 mb-2">{{ $assignment->title }}</h1>
    <p class="text-gray-600 mb-6">
        Max score: {{ $assignment->max_score }} · Pass: {{ $assignment->pass_score }}
        @if($assignment->due_at)
            · Due: {{ $assignment->due_at->format('d M Y H:i') }}
        @endif
    </p>

    @if(session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border rounded-xl p-6 mb-6">
        <h2 class="font-semibold mb-3">Instructions</h2>
        @if($assignment->instructions)
            <div class="prose max-w-none text-gray-800 mb-4">
                {!! $assignment->instructions !!}
            </div>
        @else
            <p class="text-gray-500 mb-4">No instructions provided.</p>
        @endif

        @if($assignment->attachment)
            <a href="{{ asset('storage/' . ltrim($assignment->attachment, '/')) }}"
               target="_blank"
               class="inline-block bg-tta text-white px-4 py-2 rounded-lg text-sm font-medium">
                Download assignment file
            </a>
        @endif
    </div>

    @if($submission && $submission->status === 'graded')
        <div class="bg-white border rounded-xl p-6 mb-6">
            <h2 class="font-semibold mb-2">Your grade</h2>
            <p class="text-lg font-bold text-tta">
                {{ $submission->score }} / {{ $assignment->max_score }}
            </p>
            @if($submission->feedback)
                <div class="mt-3 text-gray-700">
                    <div class="font-medium mb-1">Teacher feedback</div>
                    <div class="whitespace-pre-line">{{ $submission->feedback }}</div>
                </div>
            @endif
        </div>
    @endif

    <div class="bg-white border rounded-xl p-6">
        <h2 class="font-semibold mb-4">
            {{ $submission ? 'Update your submission' : 'Submit assignment' }}
        </h2>

        @if($submission)
            <p class="text-sm text-gray-500 mb-4">
                Last submitted: {{ $submission->submitted_at?->format('d M Y H:i') }}
                · Status: {{ ucfirst($submission->status) }}
            </p>
        @endif

        <form method="POST"
              action="{{ route('student.assignment.submit', [$course->slug, $assignment->slug]) }}"
              enctype="multipart/form-data"
              class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1">Written answer (optional)</label>
                <textarea name="content" rows="6"
                          class="w-full border rounded-lg px-3 py-2">{{ old('content', $submission->content ?? '') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Upload file (PDF/Word/image)</label>
                <input type="file" name="file" class="w-full border rounded-lg px-3 py-2">
            </div>

            <button type="submit" class="w-full bg-tta text-white font-bold py-3 rounded-xl">
                {{ $submission ? 'Resubmit Assignment' : 'Submit Assignment' }}
            </button>
        </form>
    </div>
</div>
@endsection
