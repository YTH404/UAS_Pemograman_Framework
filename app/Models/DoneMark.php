<?php

namespace App\Models;

use InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoneMark extends Model
{
    public const ASSIGNMENT = 'assignment_id';
    public const ATTENDANCE = 'attendance_id';
    public const LEARNING_MATERIAL = 'learning_material_id';

    protected $fillable = [
        'student_id',
        'assignment_id',
        'attendance_id',
        'learning_material_id',
        'is_done',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $doneMark) {
            if ($doneMark->filledActivityReferenceCount() !== 1) {
                throw new InvalidArgumentException('A done mark must reference exactly one activity.');
            }
        });
    }

    public static function activityColumns(): array
    {
        return [
            self::ASSIGNMENT,
            self::ATTENDANCE,
            self::LEARNING_MATERIAL,
        ];
    }

    public static function createForCourseStudents(Course $course, string $activityColumn, int $activityId): void
    {
        self::ensureActivityColumn($activityColumn);

        $timestamp = now();
        $rows = StudentClass::where('class_id', $course->class_id)
            ->whereHas('student')
            ->pluck('student_id')
            ->map(fn ($studentId) => [
                'student_id' => $studentId,
                $activityColumn => $activityId,
                'is_done' => false,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ])
            ->all();

        if ($rows !== []) {
            self::insertOrIgnore($rows);
        }
    }

    public static function ensureForStudent(int $studentId, string $activityColumn, int $activityId): self
    {
        self::ensureActivityColumn($activityColumn);

        return self::firstOrCreate([
            'student_id' => $studentId,
            $activityColumn => $activityId,
        ]);
    }

    public static function markDone(int $studentId, string $activityColumn, int $activityId): self
    {
        $doneMark = self::ensureForStudent($studentId, $activityColumn, $activityId);
        $doneMark->update(['is_done' => true]);

        return $doneMark;
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class, 'assignment_id');
    }

    public function attendance(): BelongsTo
    {
        return $this->belongsTo(Attendance::class, 'attendance_id');
    }

    public function learningMaterial(): BelongsTo
    {
        return $this->belongsTo(LearningMaterial::class, 'learning_material_id');
    }

    public function activityColumn(): ?string
    {
        return collect(self::activityColumns())->first(fn ($column) => filled($this->{$column}));
    }

    public function activityId(): ?int
    {
        $activityColumn = $this->activityColumn();

        return $activityColumn ? (int) $this->{$activityColumn} : null;
    }

    public function belongsToCourse(Course $course): bool
    {
        return match ($this->activityColumn()) {
            self::ASSIGNMENT => (int) $this->assignment?->course_id === (int) $course->id,
            self::ATTENDANCE => (int) $this->attendance?->course_id === (int) $course->id,
            self::LEARNING_MATERIAL => (int) $this->learningMaterial?->course_id === (int) $course->id,
            default => false,
        };
    }

    private static function ensureActivityColumn(string $activityColumn): void
    {
        if (! in_array($activityColumn, self::activityColumns(), true)) {
            throw new InvalidArgumentException('Invalid done mark activity column.');
        }
    }

    private function filledActivityReferenceCount(): int
    {
        return collect(self::activityColumns())
            ->filter(fn ($column) => filled($this->{$column}))
            ->count();
    }

    protected function casts(): array
    {
        return [
            'is_done' => 'boolean',
        ];
    }
}
