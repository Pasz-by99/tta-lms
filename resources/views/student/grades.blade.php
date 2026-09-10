@extends('layouts.app')

@section('title', 'Grades - ' . $course->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <a href="{{ route('student.course', $course->slug) }}" class="text-tta text-sm hover:underline">
        ← Back to course
    </a>

    <h1 class="text-2xl font-bold mt-2 mb-6">Grades — {{ $course->title }}</h1>

    <div class="bg-white border rounded-xl overflow-hidden mb-6">
        <div class="bg-gray-50 px-5 py-3 font-bold border-b">Quizzes</div>
        @if($quizAttempts->isEmpty())
            <div class="p-5 text-gray-500 text-sm">No quiz attempts yet.</div>
        @else
            <div class="divide-y">
                @foreach($quizAttempts as $attempt)
                    <div class="px-5 py-4 flex justify-between gap-3">
                        <div>
                            <div class="font-medium">{{ $attempt->quiz->title ?? 'Quiz' }}</div>
                            <div class="text-sm text-gray-500">
                                {{ $attempt->completed_at?->format('d M Y H:i') }}
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold">{{ $attempt->percentage }}%</div>
                            <div class="text-sm {{ $attempt->passed ? 'text-green-700' : 'text-red-600' }}">
                                {{ $attempt->passed ? 'Passed' : 'Not passed' }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="bg-white border rounded-xl overflow-hidden">
        <div class="bg-gray-50 px-5 py-3 font-bold border-b">Assignments</div>
        @if($assignmentSubmissions->isEmpty())
            <div class="p-5 text-gray-500 text-sm">No assignment submissions yet.</div>
        @else
            <div class="divide-y">
                @foreach($assignmentSubmissions as $submission)
                    <div class="px-5 py-4 flex justify-between gap-3">
                        <div>
                            <div class="font-medium">{{ $submission->assignment->title ?? 'Assignment' }}</div>
                            <div class="text-sm text-gray-500 capitalize">{{ $submission->status }}</div>
                        </div>
                        <div class="text-right">
                            @if($submission->status === 'graded')
                                <div class="font-bold">
                                    {{ $submission->score }} / {{ $submission->assignment->max_score ?? 100 }}
                                </div>
                            @else
                                <div class="text-sm text-gray-500">Awaiting mark</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
