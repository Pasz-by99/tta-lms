<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CalendarTemplate extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'type',
        'description',
        'cover_image',
        'is_published',
        'sort_order',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function events(): HasMany
    {
        return $this->hasMany(CalendarEvent::class)->orderBy('sort_order');
    }
}