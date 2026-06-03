<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $student = Student::with('studentClass.class.courses.teacher')->find($request->user()->id);
        $class = $student?->studentClass?->class;

        $courseSummary = $class?->courses
            ->map(fn ($course) => [
                'name' => $course->course_name,
                'teacher' => $course->teacher?->name ?? 'No teacher assigned',
                'progress' => 0,
                'deadline' => 'No deadline set',
            ])
            ->values()
            ->all() ?? [];

        return view('student.dashboard', compact('student', 'class', 'courseSummary'));
    }
}
