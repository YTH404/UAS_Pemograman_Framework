<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Student;
use App\Models\Submission;
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
                'assignments',
                'attendances.attendanceStudents' => fn ($query) => $query->where('student_id', $student->id),
            ])
            ->where('class_id', $class?->id)
            ->findOrFail($course);
        $this->syncMissingSubmissions($course, $student);
        $course->load([
            'assignments.submissions' => fn ($query) => $query->where('student_id', $student->id)->with('files'),
        ]);
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
        $assignmentsByMeeting = $course->assignments->groupBy(
            fn ($assignment) => $this->normalizeMeetingTitle($assignment->meeting)
        );
        $meetingsWithContent = collect([...$materialsByMeeting->keys(), ...$attendancesByMeeting->keys(), ...$assignmentsByMeeting->keys()])
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
                'assignments' => $this->assignmentCards($assignmentsByMeeting->get('Pertemuan ' . $meeting, collect()), $course->id),
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
        return [];
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

    private function syncMissingSubmissions(Course $course, Student $student): void
    {
        $course->assignments->each(fn ($assignment) => Submission::firstOrCreate([
            'assignment_id' => $assignment->id,
            'student_id' => $student->id,
        ]));
    }

    private function assignmentCards($assignments, int $courseId)
    {
        return $assignments
            ->map(function ($assignment) use ($courseId) {
                $submission = $assignment->submissions->first();
                $status = $this->assignmentStatus($assignment, $submission);

                return [
                    'id' => $assignment->id,
                    'title' => $assignment->title,
                    'description' => $assignment->description,
                    'started_at' => $assignment->started_at,
                    'ended_at' => $assignment->ended_at,
                    'status' => $status,
                    'submit_url' => route('student.course.assignments.submit', [$courseId, $assignment->id]),
                    'files' => $submission?->files?->map(fn ($file) => [
                            'name' => $file->original_name,
                            'url' => $file->fileUrl(),
                        ])
                        ->values() ?? collect(),
                ];
            })
            ->values();
    }

    private function assignmentStatus($assignment, $submission): array
    {
        if (! $assignment->hasStarted()) {
            return [
                'label' => 'Not opened',
                'variant' => 'muted',
                'can_submit' => false,
                'button_label' => null,
            ];
        }

        if ($assignment->hasEnded()) {
            return [
                'label' => $submission?->submitted_at ? '✓ Submitted' : 'Closed',
                'variant' => $submission?->submitted_at ? 'success' : 'danger',
                'can_submit' => false,
                'button_label' => null,
            ];
        }

        if ($submission?->submitted_at !== null) {
            return [
                'label' => '✓ Submitted',
                'variant' => 'success',
                'can_submit' => true,
                'button_label' => 'Replace Submission',
            ];
        }

        return [
            'label' => 'Submit Assignment',
            'variant' => 'action',
            'can_submit' => true,
            'button_label' => 'Submit Assignment',
        ];
    }
}
