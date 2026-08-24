@extends('layouts.app')

@section('title', 'Home')

@section('content')

{{-- Hero Section --}}
<section class="bg-tta text-white py-20">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-6">
            Empowering Namibia’s Farmers Through Practical Training
        </h1>
        <p class="text-xl md:text-2xl mb-8 text-green-100 max-w-3xl mx-auto">
            Tinahls Triad Agro offers hands-on agricultural courses in livestock, crop production, agribusiness and more.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('courses.index') }}" class="bg-white text-tta font-bold px-8 py-3 rounded-lg hover:bg-gray-100 transition">
                View Courses
            </a>
            <a href="{{ route('contact') }}" class="border-2 border-white text-white font-bold px-8 py-3 rounded-lg hover:bg-white hover:text-tta transition">
                Contact to Enroll
            </a>
        </div>
    </div>
</section>

{{-- Mission & Vision --}}
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-2 gap-12">
        <div class="bg-green-50 p-8 rounded-2xl border border-green-100">
            <h2 class="text-2xl font-bold text-tta mb-4">Our Mission</h2>
            <p class="text-gray-700 leading-relaxed">
                To provide practical, accessible and high-quality agricultural training that empowers farmers, youth and women to improve productivity, food security and livelihoods across Namibia.
            </p>
        </div>
        <div class="bg-green-50 p-8 rounded-2xl border border-green-100">
            <h2 class="text-2xl font-bold text-tta mb-4">Our Vision</h2>
            <p class="text-gray-700 leading-relaxed">
                To be the leading agricultural training institution in Namibia, producing skilled and confident agripreneurs who drive sustainable development in the sector.
            </p>
        </div>
    </div>
</section>

{{-- 7 Pillars --}}
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-3">Our 7 Service Pillars</h2>
            <p class="text-gray-600">Comprehensive training across the key areas of agriculture</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($categories as $category)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-green-100 text-tta rounded-lg flex items-center justify-center text-xl font-bold mb-4">
                        {{ $loop->iteration }}
                    </div>
                    <h3 class="font-bold text-lg mb-2">{{ $category->name }}</h3>
                    <p class="text-gray-600 text-sm">{{ $category->description }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Featured Courses --}}
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center mb-10">
            <h2 class="text-3xl font-bold text-gray-900">Featured Courses</h2>
            <a href="{{ route('courses.index') }}" class="text-tta font-semibold hover:underline">View all →</a>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($courses as $course)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition">
                    <div class="h-40 bg-gradient-to-br from-green-600 to-green-800 flex items-center justify-center">
                        <span class="text-white text-4xl font-bold opacity-30">TTA</span>
                    </div>
                    <div class="p-6">
                        <div class="text-xs font-semibold text-tta mb-2">{{ $course->category->name }}</div>
                        <h3 class="font-bold text-lg mb-2">{{ $course->title }}</h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $course->short_description }}</p>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-tta">N$ {{ number_format($course->price, 0) }}</span>
                            <a href="{{ route('courses.show', $course->slug) }}" class="text-sm bg-tta text-white px-4 py-2 rounded-lg hover:bg-tta-dark">
                                View Course
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-16 bg-tta text-white text-center">
    <div class="max-w-3xl mx-auto px-4">
        <h2 class="text-3xl font-bold mb-4">Ready to Start Learning?</h2>
        <p class="text-green-100 mb-8">Contact us to enroll in any of our practical agricultural training courses.</p>
        <a href="{{ route('contact') }}" class="bg-white text-tta font-bold px-8 py-3 rounded-lg hover:bg-gray-100 inline-block">
            Contact Us to Enroll
        </a>
    </div>
</section>

@endsection