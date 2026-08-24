<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'student_number',
        'must_change_password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'must_change_password' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'enrollments')
                    ->withPivot('status', 'enrolled_at')
                    ->withTimestamps();
    }

    public function lessonProgress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    /**
     * Generate next student number (TTA-2026-0001, TTA-2026-0002, ...)
     */
    public static function generateStudentNumber(): string
    {
        $year = date('Y');
        $prefix = "TTA-{$year}-";

        $last = self::where('student_number', 'like', $prefix . '%')
            ->orderBy('student_number', 'desc')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->student_number, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}