@extends('layouts.app')

@section('title', 'My Dashboard')

@section('content')
<div class="bg-tta text-white py-10">
    <div class="max-w-6xl mx-auto px-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold">Welcome, {{ auth()->user()->name }}</h1>
            <p class="text-green-100 mt-1">Your learning dashboard</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('student.calendars.index') }}" 
               class="bg-white text-tta font-semibold px-5 py-2 rounded-lg hover:bg-green-50 transition">
                Farm Calendars
            </a>
            <a href="{{ route('courses.index') }}" 
               class="border border-white text-white font-semibold px-5 py-2 rounded-lg hover:bg-white hover:text-tta transition">
                Browse Courses
            </a>
        </div>
    </div>
</div>

<div class="max-w-6xl mx-auto px-4 py-10">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-2xl font-bold">My Enrollments</h2>
        <a href="{{ route('courses.index') }}" class="text-tta font-semibold hover:underline">Browse more courses →</a>
    </div>

    @if($enrollments->count() > 0)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($enrollments as $enrollment)
                <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                    <div class="h-32 bg-gradient-to-br from-green-600 to-green-800"></div>
                    <div class="p-5">
                        <div class="text-xs text-tta font-semibold mb-1">{{ $enrollment->course->category->name ?? '' }}</div>
                        <h3 class="font-bold text-lg mb-2">{{ $enrollment->course->title }}</h3>
                        <div class="flex items-center justify-between mt-4">
                            <span class="text-xs px-2 py-1 rounded-full 
                                {{ $enrollment->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ ucfirst($enrollment->status) }}
                            </span>

                            @if($enrollment->status === 'active')
                                <a href="{{ route('student.course', $enrollment->course->slug) }}" 
                                   class="text-sm bg-tta text-white px-4 py-1.5 rounded-lg hover:bg-tta-dark">
                                    Continue
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl border p-12 text-center">
            <p class="text-gray-500 mb-4">You are not enrolled in any course yet.</p>
            <a href="{{ route('courses.index') }}" class="inline-block bg-tta text-white px-6 py-2 rounded-lg">
                Browse Courses
            </a>
        </div>
    @endif
</div>
@endsection