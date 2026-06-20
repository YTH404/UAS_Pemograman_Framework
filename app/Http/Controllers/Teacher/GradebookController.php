<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Services\CourseGradeCalculator;
use Illuminate\Http\Request;

class GradebookController extends Controller
{
    public function index(Request $request, string $course, CourseGradeCalculator $calculator)
    {
        $course = Course::with(['classes', 'gradeWeights'])
            ->where('teacher_id', $request->user()->id)
            ->findOrFail($course);
        $gradebook = $calculator->gradebook($course);

        return view('teacher.course.gradebook', compact('course', 'gradebook'));
    }
}
