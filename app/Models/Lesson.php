<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Lesson extends Model
{
    protected $fillable = [
        'course_id',
        'unit_id',
        'title',
        'slug',
        'content',
        'content_type',
        'file_path',
        'video_url',
        'duration_minutes',
        'sort_order',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function ($lesson) {
            if (empty($lesson->slug)) {
                $lesson->slug = Str::slug($lesson->title);
            }
        });
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function progress()
    {
        return $this->hasMany(LessonProgress::class);
    }
}
