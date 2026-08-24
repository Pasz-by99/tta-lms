@extends('layouts.app')

@section('title', $template->title)

@section('content')
<div class="bg-tta text-white py-10">
    <div class="max-w-4xl mx-auto px-4">
        <a href="{{ route('student.calendars.index') }}" class="text-green-200 text-sm hover:underline">← All Calendars</a>
        <div class="text-sm text-green-200 mt-2 uppercase">{{ $template->type }}</div>
        <h1 class="text-3xl font-bold mt-1">{{ $template->title }}</h1>
        @if($template->description)
            <p class="text-green-100 mt-2">{{ $template->description }}</p>
        @endif
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 py-10">
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <div class="p-6 border-b bg-gray-50">
            <h2 class="text-xl font-bold">Recommended Activities</h2>
            <p class="text-sm text-gray-500 mt-1">Follow this schedule for best results</p>
        </div>

        @if($template->events->count() > 0)
            <div class="divide-y">
                @foreach($template->events as $event)
                    <div class="p-5 md:p-6 hover:bg-gray-50 transition">
                        <div class="flex flex-col md:flex-row md:items-start gap-3">
                            <div class="md:w-48 flex-shrink-0">
                                <div class="text-sm font-bold text-tta">{{ $event->timing }}</div>
                                @if($event->category)
                                    <span class="inline-block mt-1 text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded">
                                        {{ $event->category }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-lg">{{ $event->title }}</h3>
                                @if($event->description)
                                    <p class="text-gray-600 mt-1 text-sm leading-relaxed">{{ $event->description }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-12 text-center text-gray-500">
                No activities have been added to this calendar yet.
            </div>
        @endif
    </div>

    <div class="mt-8 text-center">
        <a href="{{ route('student.calendars.index') }}" class="text-tta font-semibold hover:underline">
            ← Back to all calendars
        </a>
    </div>
</div>
@endsection