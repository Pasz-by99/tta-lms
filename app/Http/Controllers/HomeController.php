<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $courses = Course::where('is_published', true)
            ->with('category')
            ->latest()
            ->take(6)
            ->get();

        return view('pages.home', compact('categories', 'courses'));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function certificate(\App\Models\Certificate $certificate)
{
    return view('pages.certificate', compact('certificate'));
}
}