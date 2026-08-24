@extends('layouts.app')

@section('title', 'Farm Management Calendars')

@section('content')
<div class="bg-tta text-white py-10">
    <div class="max-w-6xl mx-auto px-4">
        <a href="{{ route('student.dashboard') }}" class="text-green-200 text-sm hover:underline">← Back to Dashboard</a>
        <h1 class="text-3xl font-bold mt-2">Farm Management Calendars</h1>
        <p class="text-green-100 mt-1">Recommended schedules for livestock and crop production</p>
    </div>
</div>

<div class="max-w-6xl mx-auto px-4 py-10">
    @if($templates->count() > 0)
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($templates as $template)
                <a href="{{ route('student.calendars.show', $template->slug) }}" 
                   class="bg-white rounded-xl shadow-sm border overflow-hidden hover:shadow-md transition block">
                    <div class="h-36 bg-gradient-to-br from-green-600 to-green-800 flex items-center justify-center">
                        <span class="text-white text-4xl font-bold opacity-40">
                            {{ strtoupper(substr($template->type, 0, 1)) }}
                        </span>
                    </div>
                    <div class="p-5">
                        <div class="text-xs font-semibold text-tta uppercase mb-1">{{ $template->type }}</div>
                        <h3 class="font-bold text-lg mb-2">{{ $template->title }}</h3>
                        <p class="text-sm text-gray-600 line-clamp-2 mb-3">{{ $template->description }}</p>
                        <div class="text-sm text-gray-500">
                            {{ $template->events_count }} activities
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl border p-12 text-center text-gray-500">
            No calendar templates available yet.
        </div>
    @endif
</div>
@endsection