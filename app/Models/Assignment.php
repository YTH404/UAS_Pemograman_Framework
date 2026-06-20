<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    public const TYPE_TUGAS = 'tugas';
    public const TYPE_QUIZ = 'quiz';
    public const TYPE_UTS = 'uts';
    public const TYPE_UAS = 'uas';

    protected $fillable = [
        'course_id',
        'meeting',
        'assignment_type',
        'title',
        'description',
        'started_at',
        'ended_at',
    ];

    public static function types(): array
    {
        return [
            self::TYPE_TUGAS,
            self::TYPE_QUIZ,
            self::TYPE_UTS,
            self::TYPE_UAS,
        ];
    }

    public static function typeOptions(): array
    {
        return [
            self::TYPE_TUGAS => 'Tugas',
            self::TYPE_QUIZ => 'Quiz',
            self::TYPE_UTS => 'UTS',
            self::TYPE_UAS => 'UAS',
        ];
    }

    public function typeLabel(): string
    {
        return self::typeOptions()[$this->assignment_type] ?? 'Tugas';
    }

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
