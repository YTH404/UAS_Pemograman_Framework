<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseGradeWeightController extends Controller
{
    public function store(Request $request, string $course)
    {
        $course = Course::with('gradeWeights')
            ->where('teacher_id', $request->user()->id)
            ->findOrFail($course);

        if ($course->gradeWeights) {
            return redirect()
                ->route('teacher.course.show', $course->id)
                ->with('error', 'Grade weights have already been locked for this course.');
        }

        $validatedData = $request->validate([
            'attendance_weight' => 'required|integer|min:0|max:100',
            'tugas_weight' => 'required|integer|min:0|max:100',
            'quiz_weight' => 'required|integer|min:0|max:100',
            'uts_weight' => 'required|integer|min:0|max:100',
            'uas_weight' => 'required|integer|min:0|max:100',
        ]);

        if (array_sum($validatedData) !== 100) {
            return redirect()
                ->route('teacher.course.show', $course->id)
                ->withInput()
                ->with('error', 'Grade weights must total exactly 100%.');
        }

        $course->gradeWeights()->create([
            ...$validatedData,
            'locked_at' => now(),
        ]);

        return redirect()
            ->route('teacher.course.show', $course->id)
            ->with('success', 'Grade weights locked successfully.');
    }
}
