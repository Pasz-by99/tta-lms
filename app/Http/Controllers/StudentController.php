<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $enrollments = Enrollment::with('course.category')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('student.dashboard', compact('enrollments'));
    }

    public function myCourses()
    {
        $user = Auth::user();

        $courses = $user->courses()
            ->with('category')
            ->get();

        return view('student.courses', compact('courses'));
    }

    public function course($slug)
    {
        $user = Auth::user();

        $course = Course::where('slug', $slug)
            ->with(['lessons' => function ($q) {
                $q->where('is_published', true)->orderBy('sort_order');
            }])
            ->firstOrFail();

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment) {
            return redirect()->route('student.dashboard')
                ->with('error', 'You are not enrolled in this course.');
        }

        // Treat pending/active/completed as accessible for now
        if (!in_array($enrollment->status, ['pending', 'active', 'completed'], true)) {
            return redirect()->route('student.dashboard')
                ->with('error', 'Your enrollment is not active.');
        }

        $completedLessons = LessonProgress::where('user_id', $user->id)
            ->where('is_completed', true)
            ->whereIn('lesson_id', $course->lessons->pluck('id'))
            ->pluck('lesson_id')
            ->toArray();

        return view('student.course', compact('course', 'enrollment', 'completedLessons'));
    }

    public function lesson($courseSlug, $lessonSlug)
    {
        $user = Auth::user();

        $course = Course::where('slug', $courseSlug)->firstOrFail();

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment) {
            abort(403, 'You are not enrolled in this course.');
        }

        $lesson = Lesson::where('course_id', $course->id)
            ->where('slug', $lessonSlug)
            ->where('is_published', true)
            ->firstOrFail();

        $lessons = $course->lessons()
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->get();

        $progress = LessonProgress::firstOrCreate(
            [
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
            ],
            ['is_completed' => false]
        );

        return view('student.lesson', compact('course', 'lesson', 'lessons', 'progress'));
    }

    public function markComplete(Request $request, $courseSlug, $lessonSlug)
    {
        $user = Auth::user();

        $course = Course::where('slug', $courseSlug)->firstOrFail();

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment) {
            abort(403, 'You are not enrolled in this course.');
        }

        $lesson = Lesson::where('course_id', $course->id)
            ->where('slug', $lessonSlug)
            ->firstOrFail();

        LessonProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
            ],
            [
                'is_completed' => true,
                'completed_at' => now(),
            ]
        );

        // Auto-complete course + certificate when all lessons done
        $totalLessons = $course->lessons()->where('is_published', true)->count();

        $completedLessons = LessonProgress::where('user_id', $user->id)
            ->where('is_completed', true)
            ->whereIn('lesson_id', $course->lessons()->pluck('id'))
            ->count();

        if ($totalLessons > 0 && $completedLessons >= $totalLessons) {
            $existing = \App\Models\Certificate::where('student_name', $user->name)
                ->where('course_id', $course->id)
                ->first();

            if (!$existing) {
                \App\Models\Certificate::create([
                    'certificate_number' => 'TTA-' . strtoupper(\Illuminate\Support\Str::random(8)),
                    'student_name' => $user->name,
                    'student_phone' => $user->phone,
                    'course_id' => $course->id,
                    'issued_date' => now(),
                    'issued_by' => 'Tinahls Triad Agro',
                    'notes' => 'Automatically issued after completing all lessons',
                ]);
            }

            $enrollment->update(['status' => 'completed']);

            return back()->with('success', 'Congratulations! Course completed and certificate generated.');
        }

        return back()->with('success', 'Lesson marked as completed!');
    }
}