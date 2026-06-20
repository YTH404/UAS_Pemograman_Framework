<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\AttendanceStudent;
use App\Models\Course;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\Submission;
use Illuminate\Http\Request;

class StudentActivityReportController extends Controller
{
    public function index(Request $request, string $course)
    {
        $course = Course::with('classes')
            ->where('teacher_id', $request->user()->id)
            ->findOrFail($course);

        $studentClasses = StudentClass::with('student')
            ->where('class_id', $course->class_id)
            ->whereHas('student')
            ->get()
            ->sortBy(fn (StudentClass $studentClass) => $studentClass->student->name)
            ->values();

        $selectedStudentId = $request->integer('student_id');
        $selectedStudentClass = null;

        if ($studentClasses->isNotEmpty()) {
            $selectedStudentClass = $selectedStudentId
                ? $studentClasses->firstWhere('student_id', $selectedStudentId)
                : $studentClasses->first();

            abort_if($selectedStudentId && ! $selectedStudentClass, 404);
        }

        $selectedStudent = $selectedStudentClass?->student;
        $selectedStudentIds = $selectedStudent ? [$selectedStudent->id] : [];

        $attendances = $course->attendances()
            ->with(['attendanceStudents' => fn ($query) => $query->whereIn('student_id', $selectedStudentIds)])
            ->orderBy('started_at')
            ->orderBy('id')
            ->get();

        $assignments = $course->assignments()
            ->with(['submissions' => fn ($query) => $query->whereIn('student_id', $selectedStudentIds)->with('files')])
            ->orderBy('started_at')
            ->orderBy('id')
            ->get();

        $studentReport = $selectedStudent
            ? $this->buildStudentReport($selectedStudent, $attendances, $assignments)
            : null;

        $summary = [
            'students' => $studentClasses->count(),
            'attendance_present' => $studentReport['summary']['attendance_present'] ?? 0,
            'attendance_total' => $studentReport['summary']['attendance_total'] ?? 0,
            'submitted' => $studentReport['summary']['submitted'] ?? 0,
            'graded' => $studentReport['summary']['graded'] ?? 0,
            'submission_total' => $studentReport['summary']['assignment_total'] ?? 0,
        ];

        return view('teacher.course.student-report', compact('course', 'studentClasses', 'selectedStudent', 'studentReport', 'summary'));
    }

    private function buildStudentReport(Student $student, $attendances, $assignments): array
    {
        $attendanceReports = $attendances->map(function (Attendance $attendance) use ($student) {
            $attendanceStudent = $attendance->attendanceStudents->firstWhere('student_id', $student->id);

            return [
                'attendance' => $attendance,
                'record' => $attendanceStudent,
                'status' => $this->attendanceStatus($attendance, $attendanceStudent),
            ];
        });

        $submissionReports = $assignments->map(function (Assignment $assignment) use ($student) {
            $submission = $assignment->submissions->firstWhere('student_id', $student->id);

            return [
                'assignment' => $assignment,
                'submission' => $submission,
                'files' => $submission?->files->map(fn ($file) => [
                    'name' => $file->original_name,
                    'url' => $file->fileUrl(),
                ])->values() ?? collect(),
                'status' => $this->submissionStatus($assignment, $submission),
            ];
        });

        return [
            'student' => $student,
            'attendances' => $attendanceReports,
            'submissions' => $submissionReports,
            'summary' => [
                'attendance_present' => $attendanceReports->where('status.label', 'Present')->count(),
                'attendance_total' => $attendanceReports->count(),
                'submitted' => $submissionReports
                    ->filter(fn (array $report) => in_array($report['status']['label'], ['Submitted', 'Graded'], true))
                    ->count(),
                'graded' => $submissionReports->where('status.label', 'Graded')->count(),
                'assignment_total' => $submissionReports->count(),
            ],
        ];
    }

    private function attendanceStatus(Attendance $attendance, ?AttendanceStudent $attendanceStudent): array
    {
        if ($attendanceStudent?->filled_at !== null) {
            return ['label' => 'Present', 'variant' => 'success'];
        }

        if (! $attendance->hasStarted()) {
            return ['label' => 'Not open yet', 'variant' => 'muted'];
        }

        if ($attendance->hasEnded()) {
            return ['label' => 'Absent', 'variant' => 'danger'];
        }

        return ['label' => 'Pending', 'variant' => 'warning'];
    }

    private function submissionStatus(Assignment $assignment, ?Submission $submission): array
    {
        if ($submission?->submitted_at !== null && $submission->grade !== null) {
            return ['label' => 'Graded', 'variant' => 'success'];
        }

        if ($submission?->submitted_at !== null) {
            return ['label' => 'Submitted', 'variant' => 'success'];
        }

        if (! $assignment->hasStarted()) {
            return ['label' => 'Not open yet', 'variant' => 'muted'];
        }

        if ($assignment->hasEnded()) {
            return $submission
                ? ['label' => 'Closed', 'variant' => 'danger']
                : ['label' => 'Missing', 'variant' => 'danger'];
        }

        return ['label' => 'Missing', 'variant' => 'warning'];
    }
}
