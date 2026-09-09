@extends('layouts.app')

@section('title', $quiz->title)

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <a href="{{ route('student.quizzes', $course->slug) }}" class="text-tta text-sm hover:underline">← Back to quizzes</a>

    <h1 class="text-2xl font-bold mt-2">{{ $quiz->title }}</h1>
    <p class="text-gray-600 mb-6 capitalize">{{ $quiz->type }} · Pass mark {{ $quiz->pass_percentage }}%</p>

    @if(session('success'))
        <div class="mb-4 p-4 rounded-lg bg-green-50 text-green-800">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('student.quiz.submit', [$course->slug, $quiz->slug]) }}" class="space-y-6">
        @csrf

        @foreach($quiz->questions as $index => $question)
            <div class="bg-white border rounded-xl p-5">
                <div class="font-semibold mb-3">
                    {{ $index + 1 }}. {{ $question->question_text }}
                    <span class="text-sm text-gray-500 font-normal">({{ $question->points }} pt)</span>
                </div>

                <div class="space-y-2">
                    @foreach($question->options as $option)
                        <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio"
                                   name="answers[{{ $question->id }}]"
                                   value="{{ $option->id }}"
                                   required>
                            <span>{{ $option->option_text }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach

        <button type="submit" class="w-full bg-tta text-white font-bold py-3 rounded-xl">
            Submit Quiz
        </button>
    </form>
</div>
@endsection
