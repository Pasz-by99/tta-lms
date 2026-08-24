<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    protected $fillable = [
        'certificate_number',
        'student_name',
        'student_phone',
        'course_id',
        'issued_date',
        'issued_by',
        'notes',
    ];

    protected $casts = [
        'issued_date' => 'date',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}