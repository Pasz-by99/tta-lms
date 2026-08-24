@extends('layouts.app')

@section('title', 'Courses')

@section('content')
<div class="bg-tta text-white py-12">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-3xl md:text-4xl font-bold">Our Training Courses</h1>
        <p class="mt-2 text-green-100">Practical skills for Namibian farmers and agripreneurs</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-12">
    <div class="flex flex-col lg:flex-row gap-8">
        {{-- Sidebar --}}
        <aside class="lg:w-64 flex-shrink-0">
            <div class="bg-white rounded-xl shadow-sm border p-5 sticky top-24">
                <h3 class="font-bold mb-4">Categories</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('courses.index') }}" 
                           class="block px-3 py-2 rounded-lg {{ !request('category') ? 'bg-green-100 text-tta font-semibold' : 'hover:bg-gray-50' }}">
                            All Courses
                        </a>
                    </li>
                    @foreach($categories as $cat)
                        <li>
                            <a href="{{ route('courses.index', ['category' => $cat->id]) }}" 
                               class="block px-3 py-2 rounded-lg {{ request('category') == $cat->id ? 'bg-green-100 text-tta font-semibold' : 'hover:bg-gray-50' }}">
                                {{ $cat->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </aside>

        {{-- Courses Grid --}}
        <div class="flex-1">
            <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-6">
                @forelse($courses as $course)
                    <div class="bg-white rounded-xl shadow-sm border overflow-hidden hover:shadow-md transition">
                        <div class="h-36 bg-gradient-to-br from-green-600 to-green-800"></div>
                        <div class="p-5">
                            <div class="text-xs text-tta font-semibold mb-1">{{ $course->category->name }}</div>
                            <h3 class="font-bold mb-2">{{ $course->title }}</h3>
                            <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $course->short_description }}</p>
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-tta">N$ {{ number_format($course->price, 0) }}</span>
                                <a href="{{ route('courses.show', $course->slug) }}" class="text-sm bg-tta text-white px-3 py-1.5 rounded-lg">
                                    Details
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16 text-gray-500">
                        No courses found in this category.
                    </div>
                @endforelse
            </div>

            <div class="mt-10">
                {{ $courses->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection