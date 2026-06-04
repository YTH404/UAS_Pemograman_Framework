<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceStudent extends Model
{
    protected $fillable = [
        'attendance_id',
        'student_id',
        'status',
        'filled_at',
    ];

    public function attendance(): BelongsTo
    {
        return $this->belongsTo(Attendance::class, 'attendance_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    protected function casts(): array
    {
        return [
            'filled_at' => 'datetime',
        ];
    }
}
