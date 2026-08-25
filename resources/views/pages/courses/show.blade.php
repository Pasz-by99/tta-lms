@extends('layouts.app')

@section('title', $course->title)

@section('content')
<div class="bg-tta text-white py-10">
    <div class="max-w-5xl mx-auto px-4">
        <div class="text-sm text-green-100 mb-1">{{ $course->category->name ?? '' }}</div>
        <h1 class="text-3xl font-bold">{{ $course->title }}</h1>
        <div class="mt-3 flex flex-wrap gap-2 text-sm">
            <span class="bg-white/20 px-3 py-1 rounded-full">{{ $course->level }}</span>
            <span class="bg-white/20 px-3 py-1 rounded-full">{{ $course->duration }}</span>
            <span class="bg-white/20 px-3 py-1 rounded-full font-bold">N$ {{ number_format($course->price, 0) }}</span>
        </div>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 py-10 grid lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl border p-6">
            <h2 class="text-xl font-bold mb-3">About this course</h2>
            <p class="text-gray-700 whitespace-pre-line">{{ $course->short_description ?: $course->description }}</p>
        </div>

        <div class="bg-white rounded-xl border p-6">
            <h2 class="text-xl font-bold mb-4">Course Outline</h2>
            <p class="text-sm text-gray-500 mb-4">Lesson titles only. Full content is available after enrollment and payment.</p>

            @if($course->lessons && $course->lessons->count())
                <div class="space-y-2">
                    @foreach($course->lessons->where('is_published', true) as $i => $lesson)
                        <div class="flex items-center gap-3 p-3 rounded-lg border bg-gray-50">
                            <div class="w-8 h-8 rounded-full bg-green-100 text-tta flex items-center justify-center text-sm font-bold">{{ $i+1 }}</div>
                            <div class="flex-1">
                                <div class="font-medium">{{ $lesson->title }}</div>
                                <div class="text-xs text-gray-500 capitalize">{{ $lesson->content_type }} @if($lesson->duration_minutes)• {{ $lesson->duration_minutes }} min @endif</div>
                            </div>
                            <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">Locked</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">Outline coming soon.</p>
            @endif
        </div>
    </div>

    <div>
        <div class="bg-white rounded-xl border p-6 sticky top-24">
            <div class="text-3xl font-bold text-tta mb-1">N$ {{ number_format($course->price, 0) }}</div>
            <p class="text-sm text-gray-500 mb-4">Manual payment only</p>

            <div class="bg-green-50 border border-green-100 rounded-lg p-4 text-sm mb-4">
                <p class="font-semibold text-tta mb-2">How to enroll</p>
                <ol class="list-decimal list-inside space-y-1 text-gray-700">
                    <li>Contact us on WhatsApp/Call</li>
                    <li>Make payment</li>
                    <li>We activate your student account</li>
                </ol>
            </div>

            <div class="text-sm space-y-1 mb-4">
                <div class="font-semibold">Call / WhatsApp</div>
                <div class="font-bold">+264 81 318 8489</div>
                <div class="font-bold">+264 81 705 0652</div>
                <div class="text-gray-600 mt-2">tinahsagrotriad@gmail.com</div>
            </div>

            <a href="{{ url('/contact') }}" class="block text-center bg-tta text-white font-bold py-3 rounded-lg">Contact to Enroll</a>
        </div>
    </div>
</div>
@endsection