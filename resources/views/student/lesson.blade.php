@extends('layouts.app')

@section('title', $lesson->title)

@section('content')
<div class="bg-tta text-white py-8">
    <div class="max-w-5xl mx-auto px-4">
        <a href="{{ route('student.course', $course->slug) }}" class="text-green-200 text-sm hover:underline">
            ← Back to {{ $course->title }}
        </a>
        <h1 class="text-2xl md:text-3xl font-bold mt-2">{{ $lesson->title }}</h1>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 py-10 grid lg:grid-cols-3 gap-8">

    {{-- Main Content --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border p-6 md:p-8">

            @if($lesson->content_type === 'video' && $lesson->video_url)
                <div class="aspect-video mb-6 bg-black rounded-lg overflow-hidden">
                    @php
                        $url = $lesson->video_url;
                        $youtubeId = null;
                        if (str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be')) {
                            preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches);
                            $youtubeId = $matches[1] ?? null;
                        }
                    @endphp

                    @if($youtubeId)
                        <iframe class="w-full h-full" 
                                src="https://www.youtube.com/embed/{{ $youtubeId }}" 
                                frameborder="0" allowfullscreen></iframe>
                    @else
                        <div class="flex items-center justify-center h-full text-white">
                            <a href="{{ $lesson->video_url }}" target="_blank" class="underline">Open Video</a>
                        </div>
                    @endif
                </div>
            @endif

            @if($lesson->content_type === 'text' && $lesson->content)
                <div class="prose max-w-none">
                    {!! $lesson->content !!}
                </div>
            @endif

            @if($lesson->content_type === 'file' && $lesson->file_path)
                <div class="text-center py-12">
                    <a href="{{ asset('storage/' . $lesson->file_path) }}" 
                       target="_blank"
                       class="inline-block bg-tta text-white px-8 py-3 rounded-lg font-semibold hover:bg-tta-dark">
                        Download Lesson File
                    </a>
                </div>
            @endif
        </div>

        {{-- Mark as Complete --}}
        <div class="bg-white rounded-xl shadow-sm border p-6">
            @if($progress->is_completed)
                <div class="flex items-center gap-3 text-green-700">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span class="font-semibold">You completed this lesson</span>
                    <span class="text-sm text-gray-500">on {{ $progress->completed_at?->format('d M Y') }}</span>
                </div>
            @else
                <form method="POST" action="{{ route('student.lesson.complete', [$course->slug, $lesson->slug]) }}">
                    @csrf
                    <button type="submit" class="w-full bg-tta text-white font-bold py-3 rounded-lg hover:bg-tta-dark transition">
                        Mark Lesson as Completed
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Sidebar --}}
    <div>
        <div class="bg-white rounded-xl shadow-sm border p-5 sticky top-24">
            <h3 class="font-bold mb-4">Course Lessons</h3>
            <div class="space-y-1 max-h-[28rem] overflow-y-auto">
                @foreach($lessons as $item)
                    <a href="{{ route('student.lesson', [$course->slug, $item->slug]) }}"
                       class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm
                              {{ $item->id === $lesson->id ? 'bg-green-100 text-tta font-semibold' : 'hover:bg-gray-50' }}">
                        <span class="flex-1">{{ $item->title }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection