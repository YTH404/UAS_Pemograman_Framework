<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\DoneMark;
use App\Models\Student;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function fill(Request $request, string $course, string $attendance)
    {
        $student = Student::with('studentClass.class')->findOrFail($request->user()->id);
        $class = $student->studentClass?->class;
        $course = Course::where('class_id', $class?->id)->findOrFail($course);
        $attendance = $course->attendances()->findOrFail($attendance);
        $attendanceStudent = $attendance->attendanceStudents()
            ->where('student_id', $student->id)
            ->firstOrFail();

        if (! $attendance->hasStarted()) {
            return redirect()
                ->route('student.course.show', $course->id)
                ->with('error', __('sweetalert.flash.attendance.not_open'));
        }

        if ($attendance->hasEnded()) {
            return redirect()
                ->route('student.course.show', $course->id)
                ->with('error', __('sweetalert.flash.attendance.closed'));
        }

        if ($attendanceStudent->filled_at !== null) {
            return redirect()
                ->route('student.course.show', $course->id)
                ->with('error', __('sweetalert.flash.attendance.already_filled'));
        }

        $attendanceStudent->update([
            'status' => 'present',
            'filled_at' => now(),
        ]);
        DoneMark::markDone($student->id, DoneMark::ATTENDANCE, $attendance->id);

        return redirect()->route('student.course.show', $course->id)->with('success', __('sweetalert.flash.attendance.filled'));
    }
}
