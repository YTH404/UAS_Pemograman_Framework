<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    protected $fillable = [
        'course_id',
        'meeting',
        'title',
        'description',
        'started_at',
        'ended_at',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class, 'assignment_id');
    }

    public function doneMarks(): HasMany
    {
        return $this->hasMany(DoneMark::class, 'assignment_id');
    }

    public function hasStarted(): bool
    {
        return $this->started_at === null || $this->started_at->lte(now());
    }

    public function hasEnded(): bool
    {
        return $this->ended_at !== null && $this->ended_at->lt(now());
    }

    public function isOpen(): bool
    {
        return $this->hasStarted() && ! $this->hasEnded();
    }

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }
}
