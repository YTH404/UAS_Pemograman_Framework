<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private const MAX_MEETINGS = 16;

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
        $materialsByMeeting = $course->learningMaterials->groupBy(
            fn ($material) => $this->normalizeMeetingTitle($material->meeting)
        );
        $meetingsWithContent = $materialsByMeeting
            ->keys()
            ->map(fn ($meeting) => $this->meetingNumber($meeting))
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->all();
        $unlockedMeetings = $this->unlockedMeetings($meetingsWithContent);
        $visibleMeetings = collect([...$unlockedMeetings, ...$meetingsWithContent])
            ->filter(fn ($meeting) => $meeting >= 1 && $meeting <= self::MAX_MEETINGS)
            ->unique()
            ->sort()
            ->values();

        return $visibleMeetings
            ->map(fn ($meeting) => [
                'title' => 'Pertemuan ' . $meeting,
                'items' => $this->placeholderItems($meeting),
                'materials' => $materialsByMeeting->get('Pertemuan ' . $meeting, collect())->values(),
                'can_add' => in_array($meeting, $unlockedMeetings, true),
            ])
            ->all();
    }

    private function unlockedMeetings(array $meetingsWithContent): array
    {
        $unlockedMeetings = [1];

        for ($meeting = 1; $meeting < self::MAX_MEETINGS; $meeting++) {
            if (! in_array($meeting, $meetingsWithContent, true)) {
                break;
            }

            $unlockedMeetings[] = $meeting + 1;
        }

        return $unlockedMeetings;
    }

    private function normalizeMeetingTitle(?string $meeting): string
    {
        $meetingNumber = $this->meetingNumber($meeting);

        return 'Pertemuan ' . ($meetingNumber ?: 1);
    }

    private function meetingNumber(?string $meeting): ?int
    {
        preg_match('/pertemuan\s+(\d+)/i', $meeting ?? '', $matches);
        $meetingNumber = (int) ($matches[1] ?? 0);

        return $meetingNumber >= 1 && $meetingNumber <= self::MAX_MEETINGS ? $meetingNumber : null;
    }

    private function placeholderItems(int $meeting): array
    {
        return [
            ['title' => 'Daftar hadir pertemuan ' . $meeting, 'type' => 'Attendance'],
            ['title' => $meeting === 1 ? 'Pengumpulan soal' : 'Pengumpulan project', 'type' => 'Submission'],
        ];
    }
}
