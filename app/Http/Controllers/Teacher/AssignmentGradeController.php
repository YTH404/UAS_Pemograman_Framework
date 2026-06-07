<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\StudentClass;
use App\Models\Submission;
use Illuminate\Http\Request;

class AssignmentGradeController extends Controller
{
    public function index(Request $request, string $course, string $assignment)
    {
        $teacher = $request->user();
        $course = $this->findTeacherCourse($request, $course);
        $assignment = $this->findAssignmentForCourse($course, $assignment);

        $this->syncMissingSubmissions($course, $assignment);

        $submissionsByStudent = $assignment->submissions()
            ->with(['student', 'files'])
            ->get()
            ->keyBy('student_id');

        $studentSubmissions = StudentClass::with('student')
            ->where('class_id', $course->class_id)
            ->whereHas('student')
            ->get()
            ->sortBy(fn ($studentClass) => $studentClass->student?->name ?? '')
            ->map(fn ($studentClass) => [
                'student' => $studentClass->student,
                'submission' => $submissionsByStudent->get($studentClass->student_id),
            ])
            ->values();

        $summary = [
            'total' => $studentSubmissions->count(),
            'submitted' => $studentSubmissions->filter(fn ($item) => $item['submission']?->submitted_at !== null)->count(),
            'graded' => $studentSubmissions->filter(fn ($item) => $item['submission']?->grade !== null)->count(),
        ];

        return view('teacher.assignment.grades', compact('teacher', 'course', 'assignment', 'studentSubmissions', 'summary'));
    }

    public function update(Request $request, string $course, string $assignment, string $submission)
    {
        $course = $this->findTeacherCourse($request, $course);
        $assignment = $this->findAssignmentForCourse($course, $assignment);
        $submission = $assignment->submissions()->findOrFail($submission);

        if ($submission->submitted_at === null) {
            return redirect()
                ->route('teacher.course.assignments.grades.index', [$course->id, $assignment->id])
                ->with('error', __('sweetalert.flash.grade.missing_submission'));
        }

        $validatedData = $request->validate([
            'grade' => 'nullable|integer|min:0|max:100',
        ]);

        $submission->update([
            'grade' => $request->filled('grade') ? (int) $validatedData['grade'] : null,
        ]);

        return redirect()
            ->route('teacher.course.assignments.grades.index', [$course->id, $assignment->id])
            ->with('success', __('sweetalert.flash.grade.updated'));
    }

    private function findTeacherCourse(Request $request, string $course): Course
    {
        return Course::with(['classes'])
            ->where('teacher_id', $request->user()->id)
            ->findOrFail($course);
    }

    private function findAssignmentForCourse(Course $course, string $assignment): Assignment
    {
        return $course->assignments()->findOrFail($assignment);
    }

    private function syncMissingSubmissions(Course $course, Assignment $assignment): void
    {
        $timestamp = now();
        $rows = StudentClass::where('class_id', $course->class_id)
            ->whereHas('student')
            ->pluck('student_id')
            ->map(fn ($studentId) => [
                'assignment_id' => $assignment->id,
                'student_id' => $studentId,
                'submitted_at' => null,
                'grade' => null,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ])
            ->all();

        if ($rows !== []) {
            Submission::insertOrIgnore($rows);
        }
    }
}
