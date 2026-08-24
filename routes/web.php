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
| Authentication Routes (from Breeze)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Smart redirect after login
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->role === 'admin' || $user->role === 'teacher') {
            return redirect('/admin');
        }

        return redirect()->route('student.dashboard');
    })->name('dashboard');

    // Profile routes (from Breeze)
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

    // Farm Calendars
    Route::get('/calendars', [CalendarController::class, 'index'])->name('calendars.index');
    Route::get('/calendars/{slug}', [CalendarController::class, 'show'])->name('calendars.show');
});