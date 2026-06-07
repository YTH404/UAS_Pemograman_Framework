<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attendance extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'meeting',
        'started_at',
        'ended_at',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function attendanceStudents(): HasMany
    {
        return $this->hasMany(AttendanceStudent::class, 'attendance_id');
    }

    public function doneMarks(): HasMany
    {
        return $this->hasMany(DoneMark::class, 'attendance_id');
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
