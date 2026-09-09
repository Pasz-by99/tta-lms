<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Unit extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'slug',
        'description',
        'sort_order',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function ($unit) {
            if (empty($unit->slug)) {
                $unit->slug = Str::slug($unit->title);
            }
        });
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('sort_order');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class)->orderBy('sort_order');
    }
}
