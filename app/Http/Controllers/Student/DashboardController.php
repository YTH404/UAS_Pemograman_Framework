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
        $course = Course::with(['classes', 'teacher', 'learningMaterials'])
            ->where('class_id', $class?->id)
            ->findOrFail($course);
        $meetings = $this->buildMeetings($course);

        return view('student.course.show', compact('student', 'class', 'course', 'meetings'));
    }

    private function buildMeetings(Course $course): array
    {
        $meetings = [
            'Pertemuan 1' => [
                'title' => 'Pertemuan 1',
                'items' => [
                    ['title' => 'Daftar hadir pertemuan 1', 'type' => 'Attendance', 'done' => true],
                    ['title' => 'Pengumpulan soal', 'type' => 'Submission', 'done' => false],
                ],
                'materials' => [],
            ],
            'Pertemuan 2' => [
                'title' => 'Pertemuan 2',
                'items' => [
                    ['title' => 'Daftar hadir pertemuan 2', 'type' => 'Attendance', 'done' => false],
                    ['title' => 'Pengumpulan project', 'type' => 'Submission', 'done' => false],
                ],
                'materials' => [],
            ],
            'Pertemuan 3' => [
                'title' => 'Pertemuan 3',
                'items' => [
                    ['title' => 'Daftar hadir pertemuan 3', 'type' => 'Attendance', 'done' => false],
                ],
                'materials' => [],
            ],
        ];

        foreach ($course->learningMaterials as $material) {
            $meeting = $material->meeting ?: 'Pertemuan 1';

            if (! isset($meetings[$meeting])) {
                $meetings[$meeting] = [
                    'title' => $meeting,
                    'items' => [],
                    'materials' => [],
                ];
            }

            $meetings[$meeting]['materials'][] = $material;
        }

        return array_values($meetings);
    }
}
