<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lesson extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'slug',
        'content_type',
        'video_url',
        'content',
        'file_path',
        'sort_order',
        'is_published',
        'duration_minutes',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
    public function progress()
{
    return $this->hasMany(LessonProgress::class);
}
}