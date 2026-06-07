<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\DoneMark;
use App\Models\Student;
use App\Models\Submission;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private const MAX_MEETINGS = 16;

    public function index(Request $request)
    {
        $student = Student::with([
            'studentClass.class.courses.teacher',
            'studentClass.class.courses.learningMaterials',
            'studentClass.class.courses.attendances',
            'studentClass.class.courses.assignments.submissions' => fn ($query) => $query->where('student_id', $request->user()->id),
        ])->find($request->user()->id);
        $class = $student?->studentClass?->class;

        $courseSummary = $class?->courses
            ->map(function ($course) use ($student, $class) {
                $this->syncMissingSubmissions($course, $student);
                $this->syncMissingDoneMarks($course, $student);

                return [
                    'id' => $course->id,
                    'name' => $course->course_name,
                    'class_code' => $course->classes?->class_code ?? $class?->class_code,
                    'teacher' => $course->teacher?->name ?? 'No teacher assigned',
                    'progress' => $this->progressForCourse($course, $student),
                    'deadline' => $this->nextCourseDeadline($course),
                    'assignment_total' => $course->assignments->count(),
                ];
            })
            ->values()
            ->all() ?? [];

        $upcomingAssignments = $class
            ? $this->upcomingAssignmentsForStudent($student, $class->courses->pluck('id')->all())
            : collect();

        return view('student.dashboard', compact('student', 'class', 'courseSummary', 'upcomingAssignments'));
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
        $this->syncMissingDoneMarks($course, $student);
        $course->load([
            'assignments.submissions' => fn ($query) => $query->where('student_id', $student->id)->with('files'),
        ]);
        $meetings = $this->buildMeetings($course, $student);
        $progress = $this->progressForCourse($course, $student);

        return view('student.course.show', compact('student', 'class', 'course', 'meetings', 'progress'));
    }

    private function buildMeetings(Course $course, Student $student): array
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
        $doneMarks = $this->doneMarksForCourse($course, $student);

        return $visibleMeetings
            ->map(fn ($meeting) => [
                'title' => 'Pertemuan ' . $meeting,
                'items' => $this->placeholderItems($meeting),
                'materials' => $this->materialCards($materialsByMeeting->get('Pertemuan ' . $meeting, collect()), $course->id, $doneMarks),
                'attendances' => $this->attendanceCards($attendancesByMeeting->get('Pertemuan ' . $meeting, collect()), $course->id, $doneMarks),
                'assignments' => $this->assignmentCards($assignmentsByMeeting->get('Pertemuan ' . $meeting, collect()), $course->id, $doneMarks),
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

    private function materialCards($materials, int $courseId, $doneMarks)
    {
        return $materials
            ->map(fn ($material) => [
                'model' => $material,
                'done_mark' => $this->doneMarkPayload($doneMarks, DoneMark::LEARNING_MATERIAL, $material->id, $courseId),
            ])
            ->values();
    }

    private function attendanceCards($attendances, int $courseId, $doneMarks)
    {
        return $attendances
            ->map(function ($attendance) use ($courseId, $doneMarks) {
                $attendanceStudent = $attendance->attendanceStudents->first();
                $status = $this->attendanceStatus($attendance, $attendanceStudent);

                return [
                    'id' => $attendance->id,
                    'title' => $attendance->title,
                    'started_at' => $attendance->started_at,
                    'ended_at' => $attendance->ended_at,
                    'status' => $status,
                    'fill_url' => route('student.course.attendances.fill', [$courseId, $attendance->id]),
                    'done_mark' => $this->doneMarkPayload($doneMarks, DoneMark::ATTENDANCE, $attendance->id, $courseId),
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

    private function syncMissingDoneMarks(Course $course, Student $student): void
    {
        $course->learningMaterials->each(fn ($material) => DoneMark::ensureForStudent($student->id, DoneMark::LEARNING_MATERIAL, $material->id));
        $course->attendances->each(fn ($attendance) => DoneMark::ensureForStudent($student->id, DoneMark::ATTENDANCE, $attendance->id));
        $course->assignments->each(fn ($assignment) => DoneMark::ensureForStudent($student->id, DoneMark::ASSIGNMENT, $assignment->id));
    }

    private function assignmentCards($assignments, int $courseId, $doneMarks)
    {
        return $assignments
            ->map(function ($assignment) use ($courseId, $doneMarks) {
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
                    'done_mark' => $this->doneMarkPayload($doneMarks, DoneMark::ASSIGNMENT, $assignment->id, $courseId),
                    'submitted_at' => $submission?->submitted_at,
                    'grade' => $submission?->grade,
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

    private function upcomingAssignmentsForStudent(Student $student, array $courseIds)
    {
        if ($courseIds === []) {
            return collect();
        }

        return Assignment::with([
                'course',
                'submissions' => fn ($query) => $query->where('student_id', $student->id),
            ])
            ->whereIn('course_id', $courseIds)
            ->where(function ($query) {
                $query->whereNull('ended_at')
                    ->orWhere('ended_at', '>=', now());
            })
            ->orderByRaw('ended_at IS NULL')
            ->orderBy('ended_at')
            ->orderBy('started_at')
            ->take(3)
            ->get()
            ->map(function ($assignment) {
                $submission = $assignment->submissions->first();
                $status = $this->assignmentStatus($assignment, $submission);

                return [
                    'course_id' => $assignment->course_id,
                    'course' => $assignment->course?->course_name ?? 'Unknown course',
                    'task' => $assignment->title,
                    'due' => $assignment->ended_at?->format('d M Y · H:i') ?? 'No deadline',
                    'type' => $submission?->submitted_at ? 'Submitted' : $status['label'],
                    'status' => $status,
                ];
            });
    }

    private function nextCourseDeadline(Course $course): string
    {
        $assignment = $course->assignments
            ->filter(fn ($assignment) => $assignment->ended_at === null || $assignment->ended_at->gte(now()))
            ->sortBy(fn ($assignment) => $assignment->ended_at?->timestamp ?? PHP_INT_MAX)
            ->first();

        return $assignment?->ended_at?->format('d M Y · H:i') ?? 'No active deadline';
    }

    private function progressForCourse(Course $course, Student $student): int
    {
        $total = $course->learningMaterials->count() + $course->attendances->count() + $course->assignments->count();

        if ($total === 0) {
            return 0;
        }

        return (int) round(($this->doneMarksForCourse($course, $student)->where('is_done', true)->count() / $total) * 100);
    }

    private function doneMarksForCourse(Course $course, Student $student)
    {
        $activityIds = $this->activityIds($course);

        return DoneMark::where('student_id', $student->id)
            ->where(function ($query) use ($activityIds) {
                $hasFilter = false;

                if ($activityIds[DoneMark::LEARNING_MATERIAL] !== []) {
                    $query->orWhereIn(DoneMark::LEARNING_MATERIAL, $activityIds[DoneMark::LEARNING_MATERIAL]);
                    $hasFilter = true;
                }

                if ($activityIds[DoneMark::ATTENDANCE] !== []) {
                    $query->orWhereIn(DoneMark::ATTENDANCE, $activityIds[DoneMark::ATTENDANCE]);
                    $hasFilter = true;
                }

                if ($activityIds[DoneMark::ASSIGNMENT] !== []) {
                    $query->orWhereIn(DoneMark::ASSIGNMENT, $activityIds[DoneMark::ASSIGNMENT]);
                    $hasFilter = true;
                }

                if (! $hasFilter) {
                    $query->whereRaw('1 = 0');
                }
            })
            ->get()
            ->keyBy(fn ($doneMark) => $this->doneMarkKey($doneMark));
    }

    private function activityIds(Course $course): array
    {
        return [
            DoneMark::LEARNING_MATERIAL => $course->learningMaterials->pluck('id')->all(),
            DoneMark::ATTENDANCE => $course->attendances->pluck('id')->all(),
            DoneMark::ASSIGNMENT => $course->assignments->pluck('id')->all(),
        ];
    }

    private function doneMarkPayload($doneMarks, string $activityColumn, int $activityId, int $courseId): array
    {
        $doneMark = $doneMarks->get($activityColumn . ':' . $activityId);

        return [
            'is_done' => (bool) $doneMark?->is_done,
            'toggle_url' => $doneMark ? route('student.course.done-marks.toggle', [$courseId, $doneMark->id]) : null,
        ];
    }

    private function doneMarkKey(DoneMark $doneMark): string
    {
        return $doneMark->activityColumn() . ':' . $doneMark->activityId();
    }
}
