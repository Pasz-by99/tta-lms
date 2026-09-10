<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('courses.show');

/*
|--------------------------------------------------------------------------
| Auth Routes (Breeze)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if (in_array($user->role ?? '', ['admin', 'teacher'])) {
            return redirect('/admin');
        }

        return redirect()->route('student.dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    Route::get('/my-courses', [StudentController::class, 'myCourses'])->name('courses');

    Route::get('/course/{slug}', [StudentController::class, 'course'])->name('course');
    Route::get('/course/{courseSlug}/lesson/{lessonSlug}', [StudentController::class, 'lesson'])->name('lesson');
    Route::post('/course/{courseSlug}/lesson/{lessonSlug}/complete', [StudentController::class, 'markComplete'])->name('lesson.complete');

    Route::get('/course/{courseSlug}/quizzes', [StudentController::class, 'quizzes'])->name('quizzes');
    Route::get('/course/{courseSlug}/quiz/{quizSlug}', [StudentController::class, 'showQuiz'])->name('quiz.show');
    Route::post('/course/{courseSlug}/quiz/{quizSlug}', [StudentController::class, 'submitQuiz'])->name('quiz.submit');

    // Assignments
    Route::get('/course/{courseSlug}/assignment/{assignmentSlug}', [StudentController::class, 'showAssignment'])->name('assignment.show');
    Route::post('/course/{courseSlug}/assignment/{assignmentSlug}', [StudentController::class, 'submitAssignment'])->name('assignment.submit');

    // Grades
    Route::get('/course/{courseSlug}/grades', [StudentController::class, 'grades'])->name('grades');

    // Calendars
    Route::get('/calendars', [CalendarController::class, 'index'])->name('calendars');
    Route::get('/calendars/{id}', [CalendarController::class, 'show'])->name('calendars.show');
});
