<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\DoneMark;
use App\Models\StudentClass;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class AssignmentController extends Controller
{
    private const MAX_MEETINGS = 16;

    public function store(Request $request, string $course)
    {
        $course = $this->findTeacherCourse($request, $course);
        $validatedData = $this->validateAssignment($request);
        $validatedData['meeting'] = $this->normalizeMeetingTitle($validatedData['meeting']);

        if (! $course->gradeWeights) {
            return redirect()
                ->route('teacher.course.show', $course->id)
                ->withInput()
                ->with('error', 'Set and lock grade weights before creating assignments.');
        }

        if (! $this->canCreateInMeeting($course, $validatedData['meeting'])) {
            return redirect()
                ->route('teacher.course.show', $course->id)
                ->withInput()
                ->with('error', __('sweetalert.flash.assignment.meeting_locked'));
        }

        $assignmentTypeError = $this->assignmentTypeError($course, $validatedData['assignment_type'], $validatedData['meeting']);

        if ($assignmentTypeError) {
            return redirect()
                ->route('teacher.course.show', $course->id)
                ->withInput()
                ->with('error', $assignmentTypeError);
        }

        DB::transaction(function () use ($course, $validatedData) {
            $assignment = $course->assignments()->create($validatedData);
            $timestamp = now();
            $submissionRows = StudentClass::where('class_id', $course->class_id)
                ->whereHas('student')
                ->pluck('student_id')
                ->map(fn ($studentId) => [
                    'assignment_id' => $assignment->id,
                    'student_id' => $studentId,
                    'submitted_at' => null,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ])
                ->all();

            if ($submissionRows !== []) {
                Submission::insert($submissionRows);
            }

            DoneMark::createForCourseStudents($course, DoneMark::ASSIGNMENT, $assignment->id);
        });

        return redirect()->route('teacher.course.show', $course->id)->with('success', __('sweetalert.flash.assignment.created'));
    }

    public function update(Request $request, string $course, string $assignment)
    {
        $course = $this->findTeacherCourse($request, $course);
        $assignment = $this->findAssignmentForCourse($course, $assignment);
        $validatedData = $this->validateAssignment($request, true);
        unset($validatedData['meeting']);

        $assignment->update($validatedData);

        return redirect()->route('teacher.course.show', $course->id)->with('success', __('sweetalert.flash.assignment.updated'));
    }

    private function validateAssignment(Request $request, bool $isUpdate = false): array
    {
        $rules = [
            'meeting' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'started_at' => 'required|date',
            'ended_at' => 'required|date|after:started_at',
        ];

        if (! $isUpdate) {
            $rules['assignment_type'] = ['required', Rule::in(Assignment::types())];
        }

        return $request->validate($rules);
    }

    private function findTeacherCourse(Request $request, string $course): Course
    {
        return Course::with('gradeWeights')
            ->where('teacher_id', $request->user()->id)
            ->findOrFail($course);
    }

    private function findAssignmentForCourse(Course $course, string $assignment): Assignment
    {
        return $course->assignments()->findOrFail($assignment);
    }

    private function canCreateInMeeting(Course $course, string $meeting): bool
    {
        $meetingNumber = $this->meetingNumber($meeting);

        if (! $meetingNumber) {
            return false;
        }

        return in_array($meetingNumber, $this->unlockedMeetings($course), true);
    }

    private function assignmentTypeError(Course $course, string $assignmentType, string $meeting): ?string
    {
        $meetingNumber = $this->meetingNumber($meeting);

        if ($assignmentType === Assignment::TYPE_UTS && $meetingNumber !== 8) {
            return 'UTS can only be created in Pertemuan 8.';
        }

        if ($assignmentType === Assignment::TYPE_UAS && $meetingNumber !== 16) {
            return 'UAS can only be created in Pertemuan 16.';
        }

        if (
            in_array($assignmentType, [Assignment::TYPE_UTS, Assignment::TYPE_UAS], true)
            && $course->assignments()->where('assignment_type', $assignmentType)->exists()
        ) {
            return strtoupper($assignmentType) . ' has already been created for this course.';
        }

        return null;
    }

    private function unlockedMeetings(Course $course): array
    {
        $meetingsWithContent = collect()
            ->merge($course->learningMaterials()->pluck('meeting'))
            ->merge($course->attendances()->pluck('meeting'))
            ->merge($course->assignments()->pluck('meeting'))
            ->map(fn ($meeting) => $this->meetingNumber($meeting))
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->all();
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
}
