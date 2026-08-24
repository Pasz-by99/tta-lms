<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::where('is_published', true)->with('category');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $courses = $query->orderBy('sort_order')->paginate(9);
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        return view('pages.courses.index', compact('courses', 'categories'));
    }

    public function show($slug)
{
    $course = Course::where('slug', $slug)
        ->where('is_published', true)
        ->with(['category', 'lessons' => function ($query) {
            $query->where('is_published', true)->orderBy('sort_order');
        }])
        ->firstOrFail();

    return view('pages.courses.show', compact('course'));
}
}