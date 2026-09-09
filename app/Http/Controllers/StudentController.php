<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $enrollments = Enrollment::with('course')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('student.dashboard', compact('enrollments'));
    }

    public function myCourses()
    {
        $user = auth()->user();

        $enrollments = Enrollment::with('course')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('student.courses', compact('enrollments'));
    }

    public function course($slug)
    {
        $user = auth()->user();
        $course = Course::where('slug', $slug)->firstOrFail();

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment) {
            abort(403, 'You are not enrolled in this course.');
        }

        $lessons = $course->lessons()
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $progressItems = LessonProgress::where('user_id', $user->id)
            ->whereIn('lesson_id', $lessons->pluck('id'))
            ->get()
            ->keyBy('lesson_id');

        return view('student.course', compact('course', 'lessons', 'enrollment', 'progressItems'));
    }

    public function lesson($courseSlug, $lessonSlug)
    {
        $user = auth()->user();
        $course = Course::where('slug', $courseSlug)->firstOrFail();

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment) {
            abort(403, 'You are not enrolled in this course.');
        }

        $lesson = $course->lessons()
            ->where('slug', $lessonSlug)
            ->where('is_published', true)
            ->firstOrFail();

        $progress = LessonProgress::where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->first();

        return view('student.lesson', compact('course', 'lesson', 'progress', 'enrollment'));
    }

    public function markComplete(Request $request, $courseSlug, $lessonSlug)
    {
        $user = auth()->user();
        $course = Course::where('slug', $courseSlug)->firstOrFail();

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment) {
            abort(403, 'You are not enrolled in this course.');
        }

        $lesson = $course->lessons()
            ->where('slug', $lessonSlug)
            ->where('is_published', true)
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

        // Auto certificate when all published lessons completed
        $publishedLessonIds = $course->lessons()->where('is_published', true)->pluck('id');
        $completedCount = LessonProgress::where('user_id', $user->id)
            ->whereIn('lesson_id', $publishedLessonIds)
            ->where('is_completed', true)
            ->count();

        if ($publishedLessonIds->count() > 0 && $completedCount >= $publishedLessonIds->count()) {
            Certificate::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                ],
                [
                    'certificate_number' => 'TTA-' . $course->id . '-' . $user->id . '-' . now()->format('Ymd'),
                    'issued_at' => now(),
                ]
            );

            $enrollment->update(['status' => 'completed']);
        }

        return back()->with('success', 'Lesson marked as completed.');
    }

    public function quizzes($courseSlug)
    {
        $user = auth()->user();
        $course = Course::where('slug', $courseSlug)->firstOrFail();

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment) {
            abort(403, 'You are not enrolled in this course.');
        }

        $quizzes = Quiz::where('course_id', $course->id)
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('student.quizzes', compact('course', 'quizzes'));
    }

    public function showQuiz($courseSlug, $quizSlug)
    {
        $user = auth()->user();
        $course = Course::where('slug', $courseSlug)->firstOrFail();

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment) {
            abort(403, 'You are not enrolled in this course.');
        }

        $quiz = Quiz::where('course_id', $course->id)
            ->where('slug', $quizSlug)
            ->where('is_published', true)
            ->with(['questions.options'])
            ->firstOrFail();

        return view('student.quiz-take', compact('course', 'quiz'));
    }

    public function submitQuiz(Request $request, $courseSlug, $quizSlug)
    {
        $user = auth()->user();
        $course = Course::where('slug', $courseSlug)->firstOrFail();

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment) {
            abort(403, 'You are not enrolled in this course.');
        }

        $quiz = Quiz::where('course_id', $course->id)
            ->where('slug', $quizSlug)
            ->where('is_published', true)
            ->with(['questions.options'])
            ->firstOrFail();

        $answers = $request->input('answers', []);
        $score = 0;
        $total = 0;

        $attempt = QuizAttempt::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'started_at' => now(),
            'completed_at' => now(),
        ]);

        foreach ($quiz->questions as $question) {
            $total += (int) $question->points;
            $selectedId = $answers[$question->id] ?? null;
            $isCorrect = false;

            if ($selectedId) {
                $option = $question->options->firstWhere('id', (int) $selectedId);
                if ($option && $option->is_correct) {
                    $isCorrect = true;
                    $score += (int) $question->points;
                }
            }

            QuizAnswer::create([
                'quiz_attempt_id' => $attempt->id,
                'question_id' => $question->id,
                'question_option_id' => $selectedId,
                'is_correct' => $isCorrect,
            ]);
        }

        $percentage = $total > 0 ? round(($score / $total) * 100, 2) : 0;
        $passed = $percentage >= (int) $quiz->pass_percentage;

        $attempt->update([
            'score' => $score,
            'total_points' => $total,
            'percentage' => $percentage,
            'passed' => $passed,
        ]);

        return redirect()
            ->route('student.quiz.show', [$course->slug, $quiz->slug])
            ->with('success', "Quiz submitted. Score: {$score}/{$total} ({$percentage}%). " . ($passed ? 'Passed' : 'Not passed'));
    }
}
