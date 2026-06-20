<?php

namespace App\Models;

use App\Models\Classes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'class_id',
        'teacher_id',
        'course_name',
    ];

    public function classes(): BelongsTo
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function learningMaterials(): HasMany
    {
        return $this->hasMany(LearningMaterial::class, 'course_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'course_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'course_id');
    }

    public function gradeWeights(): HasOne
    {
        return $this->hasOne(CourseGradeWeight::class, 'course_id');
    }
}
