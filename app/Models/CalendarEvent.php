<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalendarEvent extends Model
{
    protected $fillable = [
        'calendar_template_id',
        'title',
        'description',
        'timing',
        'category',
        'sort_order',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(CalendarTemplate::class, 'calendar_template_id');
    }
}