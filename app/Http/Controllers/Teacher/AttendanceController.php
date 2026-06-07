<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceStudent;
use App\Models\Course;
use App\Models\DoneMark;
use App\Models\StudentClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    private const MAX_MEETINGS = 16;

    public function store(Request $request, string $course)
    {
        $course = Course::where('teacher_id', $request->user()->id)->findOrFail($course);
        $validatedData = $this->validateAttendance($request);
        $validatedData['meeting'] = $this->normalizeMeetingTitle($validatedData['meeting']);

        if (! $this->canCreateInMeeting($course, $validatedData['meeting'])) {
            return redirect()
                ->route('teacher.course.show', $course->id)
                ->withInput()
                ->with('error', __('sweetalert.flash.attendance.meeting_locked'));
        }

        if ($course->attendances()->where('meeting', $validatedData['meeting'])->exists()) {
            return redirect()
                ->route('teacher.course.show', $course->id)
                ->withInput()
                ->with('error', __('sweetalert.flash.attendance.duplicate'));
        }

        DB::transaction(function () use ($course, $validatedData) {
            $attendance = $course->attendances()->create($validatedData);
            $timestamp = now();
            $attendanceRows = StudentClass::where('class_id', $course->class_id)
                ->whereHas('student')
                ->pluck('student_id')
                ->map(fn ($studentId) => [
                    'attendance_id' => $attendance->id,
                    'student_id' => $studentId,
                    'status' => 'absent',
                    'filled_at' => null,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ])
                ->all();

            if ($attendanceRows !== []) {
                AttendanceStudent::insert($attendanceRows);
            }

            DoneMark::createForCourseStudents($course, DoneMark::ATTENDANCE, $attendance->id);
        });

        return redirect()->route('teacher.course.show', $course->id)->with('success', __('sweetalert.flash.attendance.created'));
    }

    public function update(Request $request, string $course, string $attendance)
    {
        $course = Course::where('teacher_id', $request->user()->id)->findOrFail($course);
        $attendance = $this->findAttendanceForCourse($course, $attendance);
        $validatedData = $this->validateAttendance($request);
        unset($validatedData['meeting']);

        $attendance->update($validatedData);

        return redirect()->route('teacher.course.show', $course->id)->with('success', __('sweetalert.flash.attendance.updated'));
    }

    private function validateAttendance(Request $request): array
    {
        return $request->validate([
            'meeting' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'started_at' => 'required|date',
            'ended_at' => 'required|date|after:started_at',
        ]);
    }

    private function findAttendanceForCourse(Course $course, string $attendance): Attendance
    {
        return $course->attendances()->findOrFail($attendance);
    }

    private function canCreateInMeeting(Course $course, string $meeting): bool
    {
        $meetingNumber = $this->meetingNumber($meeting);

        if (! $meetingNumber) {
            return false;
        }

        return in_array($meetingNumber, $this->unlockedMeetings($course), true);
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
