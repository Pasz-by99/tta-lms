<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
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

        $units = $course->units()
            ->where('is_published', true)
            ->with([
                'lessons' => function ($q) {
                    $q->where('is_published', true)->orderBy('sort_order')->orderBy('id');
                },
                'quizzes' => function ($q) {
                    $q->where('is_published', true)->orderBy('sort_order')->orderBy('id');
                },
                'assignments' => function ($q) {
                    $q->where('is_published', true)->orderBy('sort_order')->orderBy('id');
                },
            ])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $orphanLessons = $course->lessons()
            ->where('is_published', true)
            ->whereNull('unit_id')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $allLessonIds = $units->pluck('lessons')->flatten()->pluck('id')
            ->merge($orphanLessons->pluck('id'))
            ->unique()
            ->values();

        $progressItems = LessonProgress::where('user_id', $user->id)
            ->whereIn('lesson_id', $allLessonIds)
            ->get()
            ->keyBy('lesson_id');

        return view('student.course', compact(
            'course',
            'units',
            'orphanLessons',
            'enrollment',
            'progressItems'
        ));
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

        $units = $course->units()
            ->where('is_published', true)
            ->with([
                'lessons' => fn ($q) => $q->where('is_published', true)->orderBy('sort_order')->orderBy('id'),
                'quizzes' => fn ($q) => $q->where('is_published', true)->orderBy('sort_order')->orderBy('id'),
                'assignments' => fn ($q) => $q->where('is_published', true)->orderBy('sort_order')->orderBy('id'),
            ])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $path = collect();

        foreach ($units as $unit) {
            foreach ($unit->lessons as $item) {
                $path->push([
                    'type' => 'lesson',
                    'id' => $item->id,
                    'title' => $item->title,
                    'slug' => $item->slug,
                ]);
            }
            foreach ($unit->quizzes as $item) {
                $path->push([
                    'type' => 'quiz',
                    'id' => $item->id,
                    'title' => $item->title,
                    'slug' => $item->slug,
                ]);
            }
            foreach ($unit->assignments as $item) {
                $path->push([
                    'type' => 'assignment',
                    'id' => $item->id,
                    'title' => $item->title,
                    'slug' => $item->slug,
                ]);
            }
        }

        $course->lessons()
            ->where('is_published', true)
            ->whereNull('unit_id')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->each(function ($item) use ($path) {
                $path->push([
                    'type' => 'lesson',
                    'id' => $item->id,
                    'title' => $item->title,
                    'slug' => $item->slug,
                ]);
            });

        Quiz::where('course_id', $course->id)
            ->where('is_published', true)
            ->whereNull('unit_id')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->each(function ($item) use ($path) {
                $path->push([
                    'type' => 'quiz',
                    'id' => $item->id,
                    'title' => $item->title,
                    'slug' => $item->slug,
                ]);
            });

        Assignment::where('course_id', $course->id)
            ->where('is_published', true)
            ->whereNull('unit_id')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->each(function ($item) use ($path) {
                $path->push([
                    'type' => 'assignment',
                    'id' => $item->id,
                    'title' => $item->title,
                    'slug' => $item->slug,
                ]);
            });

        $currentIndex = $path->search(function ($item) use ($lesson) {
            return $item['type'] === 'lesson' && $item['id'] === $lesson->id;
        });

        $previous = ($currentIndex !== false && $currentIndex > 0)
            ? $path[$currentIndex - 1]
            : null;

        $next = ($currentIndex !== false && $currentIndex < $path->count() - 1)
            ? $path[$currentIndex + 1]
            : null;

        return view('student.lesson', compact(
            'course',
            'lesson',
            'progress',
            'enrollment',
            'previous',
            'next'
        ));
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

    public function showAssignment($courseSlug, $assignmentSlug)
    {
        $user = auth()->user();
        $course = Course::where('slug', $courseSlug)->firstOrFail();

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment) {
            abort(403, 'You are not enrolled in this course.');
        }

        $assignment = Assignment::where('course_id', $course->id)
            ->where('slug', $assignmentSlug)
            ->where('is_published', true)
            ->firstOrFail();

        $submission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('user_id', $user->id)
            ->first();

        return view('student.assignment', compact('course', 'assignment', 'submission'));
    }

    public function submitAssignment(Request $request, $courseSlug, $assignmentSlug)
    {
        $user = auth()->user();
        $course = Course::where('slug', $courseSlug)->firstOrFail();

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment) {
            abort(403, 'You are not enrolled in this course.');
        }

        $assignment = Assignment::where('course_id', $course->id)
            ->where('slug', $assignmentSlug)
            ->where('is_published', true)
            ->firstOrFail();

        $request->validate([
            'content' => 'nullable|string',
            'file' => 'nullable|file|max:20480',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('assignment-submissions', 'public');
        }

        $data = [
            'content' => $request->input('content'),
            'status' => 'submitted',
            'submitted_at' => now(),
            'score' => null,
            'feedback' => null,
            'graded_at' => null,
        ];

        if ($filePath) {
            $data['file_path'] = $filePath;
        }

        AssignmentSubmission::updateOrCreate(
            [
                'assignment_id' => $assignment->id,
                'user_id' => $user->id,
            ],
            $data
        );

        return back()->with('success', 'Assignment submitted successfully. Waiting for teacher to mark.');
    }

    public function grades($courseSlug)
    {
        $user = auth()->user();
        $course = Course::where('slug', $courseSlug)->firstOrFail();

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment) {
            abort(403, 'You are not enrolled in this course.');
        }

        $quizAttempts = QuizAttempt::with('quiz')
            ->where('user_id', $user->id)
            ->whereHas('quiz', function ($q) use ($course) {
                $q->where('course_id', $course->id);
            })
            ->latest()
            ->get()
            ->unique('quiz_id')
            ->values();

        $assignmentSubmissions = AssignmentSubmission::with('assignment')
            ->where('user_id', $user->id)
            ->whereHas('assignment', function ($q) use ($course) {
                $q->where('course_id', $course->id);
            })
            ->get();

        return view('student.grades', compact('course', 'quizAttempts', 'assignmentSubmissions'));
    }
}
