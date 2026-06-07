<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\DoneMark;
use App\Models\Student;
use Illuminate\Http\Request;

class DoneMarkController extends Controller
{
    public function toggle(Request $request, string $course, string $doneMark)
    {
        $student = Student::with('studentClass.class')->findOrFail($request->user()->id);
        $class = $student->studentClass?->class;
        $course = Course::where('class_id', $class?->id)->findOrFail($course);
        $doneMark = DoneMark::with(['assignment', 'attendance', 'learningMaterial'])
            ->where('student_id', $student->id)
            ->findOrFail($doneMark);

        abort_unless($doneMark->belongsToCourse($course), 404);

        $doneMark->update([
            'is_done' => ! $doneMark->is_done,
        ]);

        return redirect()
            ->route('student.course.show', $course->id)
            ->with('success', $doneMark->is_done ? __('sweetalert.flash.done_mark.marked') : __('sweetalert.flash.done_mark.unmarked'));
    }
}
