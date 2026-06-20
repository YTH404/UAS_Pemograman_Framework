<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseGradeWeight extends Model
{
    public const COMPONENT_ATTENDANCE = 'attendance';
    public const COMPONENT_TUGAS = 'tugas';
    public const COMPONENT_QUIZ = 'quiz';
    public const COMPONENT_UTS = 'uts';
    public const COMPONENT_UAS = 'uas';

    protected $fillable = [
        'course_id',
        'attendance_weight',
        'tugas_weight',
        'quiz_weight',
        'uts_weight',
        'uas_weight',
        'locked_at',
    ];

    public static function labels(): array
    {
        return [
            self::COMPONENT_ATTENDANCE => 'Attendance',
            self::COMPONENT_TUGAS => 'Tugas',
            self::COMPONENT_QUIZ => 'Quiz',
            self::COMPONENT_UTS => 'UTS',
            self::COMPONENT_UAS => 'UAS',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function weights(): array
    {
        return [
            self::COMPONENT_ATTENDANCE => (int) $this->attendance_weight,
            self::COMPONENT_TUGAS => (int) $this->tugas_weight,
            self::COMPONENT_QUIZ => (int) $this->quiz_weight,
            self::COMPONENT_UTS => (int) $this->uts_weight,
            self::COMPONENT_UAS => (int) $this->uas_weight,
        ];
    }

    protected function casts(): array
    {
        return [
            'attendance_weight' => 'integer',
            'tugas_weight' => 'integer',
            'quiz_weight' => 'integer',
            'uts_weight' => 'integer',
            'uas_weight' => 'integer',
            'locked_at' => 'datetime',
        ];
    }
}
