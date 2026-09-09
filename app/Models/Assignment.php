<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Assignment extends Model
{
    protected $fillable = [
        'course_id',
        'unit_id',
        'title',
        'slug',
        'instructions',
        'attachment',
        'max_score',
        'pass_score',
        'due_at',
        'is_published',
        'sort_order',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'due_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($assignment) {
            if (empty($assignment->slug)) {
                $assignment->slug = Str::slug($assignment->title);
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

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }
}
