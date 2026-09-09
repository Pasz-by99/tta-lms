@extends('layouts.app')

@section('title', 'Quizzes - ' . $course->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <a href="{{ route('student.course', $course->slug) }}" class="text-tta text-sm hover:underline">← Back to course</a>
    <h1 class="text-2xl font-bold mt-2 mb-6">Quizzes & Tests — {{ $course->title }}</h1>

    @if($quizzes->isEmpty())
        <div class="bg-white border rounded-xl p-8 text-center text-gray-500">
            No published quizzes for this course yet.
        </div>
    @else
        <div class="space-y-3">
            @foreach($quizzes as $quiz)
                <a href="{{ route('student.quiz.show', [$course->slug, $quiz->slug]) }}"
                   class="block bg-white border rounded-xl p-5 hover:border-green-600 transition">
                    <div class="flex justify-between items-center gap-3">
                        <div>
                            <div class="font-semibold text-lg">{{ $quiz->title }}</div>
                            <div class="text-sm text-gray-500 capitalize">
                                {{ $quiz->type }} · Pass mark {{ $quiz->pass_percentage }}%
                            </div>
                        </div>
                        <span class="bg-tta text-white text-sm px-4 py-2 rounded-lg">Start</span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
