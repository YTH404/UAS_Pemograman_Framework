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
        $course = Course::with([
                'classes',
                'teacher',
                'learningMaterials',
                'attendances.attendanceStudents' => fn ($query) => $query->where('student_id', $student->id),
            ])
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
        $attendancesByMeeting = $course->attendances->groupBy(
            fn ($attendance) => $this->normalizeMeetingTitle($attendance->meeting)
        );
        $meetingsWithContent = collect([...$materialsByMeeting->keys(), ...$attendancesByMeeting->keys()])
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
                'attendances' => $this->attendanceCards($attendancesByMeeting->get('Pertemuan ' . $meeting, collect()), $course->id),
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
            ['title' => $meeting === 1 ? 'Pengumpulan soal' : 'Pengumpulan project', 'type' => 'Submission', 'done' => false],
        ];
    }

    private function attendanceCards($attendances, int $courseId)
    {
        return $attendances
            ->map(function ($attendance) use ($courseId) {
                $attendanceStudent = $attendance->attendanceStudents->first();
                $status = $this->attendanceStatus($attendance, $attendanceStudent);

                return [
                    'id' => $attendance->id,
                    'title' => $attendance->title,
                    'started_at' => $attendance->started_at,
                    'ended_at' => $attendance->ended_at,
                    'status' => $status,
                    'fill_url' => route('student.course.attendances.fill', [$courseId, $attendance->id]),
                ];
            })
            ->values();
    }

    private function attendanceStatus($attendance, $attendanceStudent): array
    {
        if ($attendanceStudent?->filled_at !== null) {
            return [
                'label' => '✓ Present',
                'variant' => 'success',
                'can_fill' => false,
            ];
        }

        if (! $attendance->hasStarted()) {
            return [
                'label' => 'Not opened',
                'variant' => 'muted',
                'can_fill' => false,
            ];
        }

        if ($attendance->hasEnded()) {
            return [
                'label' => 'Absent',
                'variant' => 'danger',
                'can_fill' => false,
            ];
        }

        return [
            'label' => 'Fill Attendance',
            'variant' => 'action',
            'can_fill' => true,
        ];
    }
}
