<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $teacher = $request->user();
        $assignedCourses = Course::with('classes.studentClasses')
            ->where('teacher_id', $teacher->id)
            ->get();

        $classCourses = $assignedCourses
            ->map(fn ($course) => [
                'name' => $course->course_name,
                'class_name' => $course->classes?->class_name ?? 'No class assigned',
                'student_enrolled' => $course->classes?->studentClasses->count() ?? 0,
                'pending_submissions' => 0,
                'submitted_submissions' => 0,
            ])
            ->values()
            ->all();

        $teacherTotals = [
            ['label' => 'Total class assigned', 'value' => $assignedCourses->pluck('class_id')->unique()->count(), 'note' => 'Active groups this term'],
            ['label' => 'Total course assigned', 'value' => $assignedCourses->count(), 'note' => 'Courses under your supervision'],
            ['label' => 'Visible assigned courses', 'value' => count($classCourses), 'note' => 'Courses shown on this dashboard'],
        ];

        return view('teacher.dashboard', compact('teacher', 'teacherTotals', 'classCourses'));
    }
}
