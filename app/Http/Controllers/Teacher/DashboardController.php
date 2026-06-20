<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\CourseGradeWeight;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private const MAX_MEETINGS = 16;

    public function index(Request $request)
    {
        $teacher = $request->user();
        $assignedCourses = Course::with(['classes.studentClasses', 'assignments.submissions'])
            ->where('teacher_id', $teacher->id)
            ->get();

        $classCourses = $assignedCourses
            ->map(fn ($course) => [
                'id' => $course->id,
                'name' => $course->course_name,
                'class_code' => $course->classes?->class_code,
                'class_name' => $course->classes?->class_name ?? 'No class yet',
                'student_enrolled' => $course->classes?->studentClasses->count() ?? 0,
                'pending_submissions' => $course->assignments->sum(fn ($assignment) => $assignment->submissions->filter(fn ($submission) => $submission->submitted_at === null)->count()),
                'submitted_submissions' => $course->assignments->sum(fn ($assignment) => $assignment->submissions->filter(fn ($submission) => $submission->submitted_at !== null)->count()),
            ])
            ->values()
            ->all();

        $teacherTotals = [
            ['label' => 'Total assigned classes', 'value' => $assignedCourses->pluck('class_id')->unique()->count(), 'note' => 'Active class groups this semester'],
            ['label' => 'Total assigned courses', 'value' => $assignedCourses->count(), 'note' => 'Courses under your supervision'],
        ];

        return view('teacher.dashboard', compact('teacher', 'teacherTotals', 'classCourses'));
    }

    public function showCourse(Request $request, string $course)
    {
        $teacher = $request->user();
        $course = Course::with(['classes', 'gradeWeights', 'learningMaterials', 'attendances.attendanceStudents', 'assignments.submissions.files'])
            ->where('teacher_id', $teacher->id)
            ->findOrFail($course);
        $meetings = $this->buildMeetings($course);
        $assignmentTypeOptions = Assignment::typeOptions();
        $gradeWeightLabels = CourseGradeWeight::labels();

        return view('teacher.course.show', compact('teacher', 'course', 'meetings', 'assignmentTypeOptions', 'gradeWeightLabels'));
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
                'attendances' => $this->attendanceCards($attendancesByMeeting->get('Pertemuan ' . $meeting, collect()), $course->id),
                'assignments' => $this->assignmentCards($assignmentsByMeeting->get('Pertemuan ' . $meeting, collect()), $course->id),
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
        return [];
    }

    private function attendanceCards($attendances, int $courseId)
    {
        return $attendances
            ->map(fn ($attendance) => [
                'id' => $attendance->id,
                'title' => $attendance->title,
                'started_at' => $attendance->started_at,
                'ended_at' => $attendance->ended_at,
                'update_url' => route('teacher.course.attendances.update', [$courseId, $attendance->id]),
                'filled_count' => $attendance->attendanceStudents->filter(fn ($attendanceStudent) => $attendanceStudent->filled_at !== null)->count(),
                'total_count' => $attendance->attendanceStudents->count(),
            ])
            ->values();
    }

    private function assignmentCards($assignments, int $courseId)
    {
        return $assignments
            ->map(fn ($assignment) => [
                'id' => $assignment->id,
                'assignment_type' => $assignment->assignment_type,
                'assignment_type_label' => $assignment->typeLabel(),
                'title' => $assignment->title,
                'description' => $assignment->description,
                'started_at' => $assignment->started_at,
                'ended_at' => $assignment->ended_at,
                'update_url' => route('teacher.course.assignments.update', [$courseId, $assignment->id]),
                'grade_url' => route('teacher.course.assignments.grades.index', [$courseId, $assignment->id]),
                'submitted_count' => $assignment->submissions->filter(fn ($submission) => $submission->submitted_at !== null)->count(),
                'graded_count' => $assignment->submissions->filter(fn ($submission) => $submission->grade !== null)->count(),
                'total_count' => $assignment->submissions->count(),
            ])
            ->values();
    }
}
