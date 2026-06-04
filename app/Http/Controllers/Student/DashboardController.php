<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private const MAX_MEETINGS = 16;

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
        $visibleMeetings = collect([...$this->unlockedMeetings($meetingsWithContent), ...$meetingsWithContent])
            ->filter(fn ($meeting) => $meeting >= 1 && $meeting <= self::MAX_MEETINGS)
            ->unique()
            ->sort()
            ->values();

        return $visibleMeetings
            ->map(fn ($meeting) => [
                'title' => 'Pertemuan ' . $meeting,
                'items' => $this->placeholderItems($meeting),
                'materials' => $materialsByMeeting->get('Pertemuan ' . $meeting, collect())->values(),
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
            ['title' => 'Daftar hadir pertemuan ' . $meeting, 'type' => 'Attendance', 'done' => $meeting === 1],
            ['title' => $meeting === 1 ? 'Pengumpulan soal' : 'Pengumpulan project', 'type' => 'Submission', 'done' => false],
        ];
    }
}
