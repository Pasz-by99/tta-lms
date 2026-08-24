<?php

namespace App\Http\Controllers;

use App\Models\CalendarTemplate;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    /**
     * List all published calendar templates
     * Only accessible to enrolled students (we will check a special course later)
     */
    public function index()
    {
        $templates = CalendarTemplate::where('is_published', true)
            ->withCount('events')
            ->orderBy('sort_order')
            ->get();

        return view('student.calendars.index', compact('templates'));
    }

    /**
     * Show a single calendar template with all its events
     */
    public function show($slug)
    {
        $template = CalendarTemplate::where('slug', $slug)
            ->where('is_published', true)
            ->with(['events' => function ($q) {
                $q->orderBy('sort_order');
            }])
            ->firstOrFail();

        return view('student.calendars.show', compact('template'));
    }
}