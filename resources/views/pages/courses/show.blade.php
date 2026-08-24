@extends('layouts.app')

@section('title', $course->title)

@section('content')
<div class="bg-tta text-white py-12">
    <div class="max-w-5xl mx-auto px-4">
        <div class="text-sm text-green-200 mb-2">{{ $course->category->name }}</div>
        <h1 class="text-3xl md:text-4xl font-bold">{{ $course->title }}</h1>
        <div class="mt-4 flex flex-wrap gap-4 text-sm">
            <span class="bg-white/20 px-3 py-1 rounded-full">{{ $course->level }}</span>
            <span class="bg-white/20 px-3 py-1 rounded-full">{{ $course->duration }}</span>
            <span class="bg-white/20 px-3 py-1 rounded-full font-bold">N$ {{ number_format($course->price, 0) }}</span>
            <span class="bg-white/20 px-3 py-1 rounded-full">{{ $course->lessons->count() }} Lessons</span>
        </div>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 py-12 grid lg:grid-cols-3 gap-10">

    {{-- Left Column --}}
    <div class="lg:col-span-2 space-y-8">

        {{-- About --}}
        <div class="bg-white rounded-xl shadow-sm border p-8">
            <h2 class="text-xl font-bold mb-4">About this course</h2>
            <div class="prose max-w-none text-gray-700">
                {!! nl2br(e($course->description)) !!}
            </div>
        </div>

        {{-- Lessons List --}}
        <div class="bg-white rounded-xl shadow-sm border p-8">
            <h2 class="text-xl font-bold mb-6">Course Content ({{ $course->lessons->count() }} lessons)</h2>

            @if($course->lessons->count() > 0)
                <div class="space-y-3">
                    @foreach($course->lessons->where('is_published', true) as $index => $lesson)
                        <div class="flex items-start gap-4 p-4 rounded-lg border hover:bg-gray-50 transition">
                            <div class="flex-shrink-0 w-8 h-8 bg-green-100 text-tta rounded-full flex items-center justify-center font-bold text-sm">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold">{{ $lesson->title }}</h3>
                                <div class="flex items-center gap-3 mt-1 text-sm text-gray-500">
                                    <span class="capitalize">{{ $lesson->content_type }}</span>
                                    @if($lesson->duration_minutes)
                                        <span>• {{ $lesson->duration_minutes }} min</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                @if($lesson->content_type === 'video')
                                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">Video</span>
                                @elseif($lesson->content_type === 'file')
                                    <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded">File</span>
                                @else
                                    <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded">Notes</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">Lessons will be added soon.</p>
            @endif
        </div>
    </div>

    {{-- Right Column - Enrollment Card --}}
    <div>
        <div class="bg-white rounded-xl shadow-sm border p-6 sticky top-24">
            <div class="text-3xl font-bold text-tta mb-1">N$ {{ number_format($course->price, 0) }}</div>
            <p class="text-sm text-gray-500 mb-6">Manual payment only</p>

            <div class="bg-green-50 border border-green-100 rounded-lg p-4 text-sm mb-6">
                <p class="font-semibold text-tta mb-2">How to enroll:</p>
                <ol class="list-decimal list-inside space-y-1 text-gray-700">
                    <li>Call or WhatsApp us</li>
                    <li>Make payment as instructed</li>
                    <li>We confirm your seat</li>
                </ol>
            </div>

            <div class="space-y-2 text-sm">
                <p class="font-semibold">Call / WhatsApp:</p>
                <p class="text-lg font-bold">+264 81 318 8489</p>
                <p class="text-lg font-bold">+264 81 705 0652</p>
                <p class="mt-3 text-gray-600">tinahsagrotriad@gmail.com</p>
            </div>

            <a href="{{ route('contact') }}" class="mt-6 block w-full bg-tta text-white text-center font-bold py-3 rounded-lg hover:bg-tta-dark transition">
                Contact to Enroll
            </a>
        </div>
    </div>
</div>
@endsection