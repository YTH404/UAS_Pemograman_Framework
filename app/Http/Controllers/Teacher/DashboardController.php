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
                'id' => $course->id,
                'name' => $course->course_name,
                'class_code' => $course->classes?->class_code,
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

    public function showCourse(Request $request, string $course)
    {
        $teacher = $request->user();
        $course = Course::with(['classes', 'learningMaterials'])
            ->where('teacher_id', $teacher->id)
            ->findOrFail($course);
        $meetings = $this->buildMeetings($course);

        return view('teacher.course.show', compact('teacher', 'course', 'meetings'));
    }

    private function buildMeetings(Course $course): array
    {
        $meetings = [
            'Pertemuan 1' => [
                'title' => 'Pertemuan 1',
                'items' => [
                    ['title' => 'Daftar hadir pertemuan 1', 'type' => 'Attendance'],
                    ['title' => 'Pengumpulan soal', 'type' => 'Submission'],
                ],
                'materials' => [],
            ],
            'Pertemuan 2' => [
                'title' => 'Pertemuan 2',
                'items' => [
                    ['title' => 'Daftar hadir pertemuan 2', 'type' => 'Attendance'],
                    ['title' => 'Pengumpulan project', 'type' => 'Submission'],
                ],
                'materials' => [],
            ],
            'Pertemuan 3' => [
                'title' => 'Pertemuan 3',
                'items' => [
                    ['title' => 'Daftar hadir pertemuan 3', 'type' => 'Attendance'],
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
