<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
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
                'id' => $course->id,
                'name' => $course->course_name,
                'class_code' => $course->classes?->class_code ?? $class?->class_code,
                'teacher' => $course->teacher?->name ?? 'No teacher assigned',
                'progress' => 0,
                'deadline' => 'No deadline set',
            ])
            ->values()
            ->all() ?? [];

        return view('student.dashboard', compact('student', 'class', 'courseSummary'));
    }

    public function showCourse(Request $request, string $course)
    {
        $student = Student::with('studentClass.class')->findOrFail($request->user()->id);
        $class = $student->studentClass?->class;
        $course = Course::with(['classes', 'teacher'])
            ->where('class_id', $class?->id)
            ->findOrFail($course);
        $meetings = $this->placeholderMeetings();

        return view('student.course.show', compact('student', 'class', 'course', 'meetings'));
    }

    private function placeholderMeetings(): array
    {
        return [
            [
                'title' => 'Pertemuan 1',
                'items' => [
                    ['title' => 'Daftar hadir pertemuan 1', 'type' => 'Attendance', 'done' => true],
                    ['title' => 'Materi 1', 'type' => 'Materials', 'done' => true],
                    ['title' => 'Pengumpulan soal', 'type' => 'Submission', 'done' => false],
                ],
            ],
            [
                'title' => 'Pertemuan 2',
                'items' => [
                    ['title' => 'Daftar hadir pertemuan 2', 'type' => 'Attendance', 'done' => false],
                    ['title' => 'Materi 2', 'type' => 'Materials', 'done' => false],
                    ['title' => 'Pengumpulan project', 'type' => 'Submission', 'done' => false],
                ],
            ],
            [
                'title' => 'Pertemuan 3',
                'items' => [
                    ['title' => 'Daftar hadir pertemuan 3', 'type' => 'Attendance', 'done' => false],
                    ['title' => 'Materi 3', 'type' => 'Materials', 'done' => false],
                ],
            ],
        ];
    }
}
