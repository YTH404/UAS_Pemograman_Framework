<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\CourseGradeWeight;
use App\Models\Student;
use App\Models\StudentClass;
use Illuminate\Support\Collection;

class CourseGradeCalculator
{
    public function gradebook(Course $course): array
    {
        $course->loadMissing('gradeWeights');

        $studentClasses = StudentClass::with('student')
            ->where('class_id', $course->class_id)
            ->whereHas('student')
            ->get()
            ->sortBy(fn (StudentClass $studentClass) => $studentClass->student->name)
            ->values();
        $studentIds = $studentClasses->pluck('student_id')->all();

        $attendances = $course->attendances()
            ->with(['attendanceStudents' => fn ($query) => $query->whereIn('student_id', $studentIds)])
            ->orderBy('started_at')
            ->orderBy('id')
            ->get();
        $assignments = $course->assignments()
            ->with(['submissions' => fn ($query) => $query->whereIn('student_id', $studentIds)])
            ->orderBy('started_at')
            ->orderBy('id')
            ->get();
        $hasUas = $assignments->where('assignment_type', Assignment::TYPE_UAS)->isNotEmpty();
        $weights = $course->gradeWeights?->weights() ?? $this->emptyWeights();

        return [
            'is_configured' => $course->gradeWeights !== null,
            'is_available' => $course->gradeWeights !== null && $hasUas,
            'has_uas' => $hasUas,
            'weights' => $weights,
            'labels' => CourseGradeWeight::labels(),
            'rows' => $studentClasses
                ->map(fn (StudentClass $studentClass) => $this->studentRow(
                    $studentClass->student,
                    $weights,
                    $attendances,
                    $assignments
                ))
                ->values(),
        ];
    }

    private function studentRow(Student $student, array $weights, Collection $attendances, Collection $assignments): array
    {
        $components = [
            CourseGradeWeight::COMPONENT_ATTENDANCE => $this->attendanceComponent($student, $attendances, $weights[CourseGradeWeight::COMPONENT_ATTENDANCE]),
            CourseGradeWeight::COMPONENT_TUGAS => $this->assignmentComponent($student, $assignments, Assignment::TYPE_TUGAS, $weights[CourseGradeWeight::COMPONENT_TUGAS]),
            CourseGradeWeight::COMPONENT_QUIZ => $this->assignmentComponent($student, $assignments, Assignment::TYPE_QUIZ, $weights[CourseGradeWeight::COMPONENT_QUIZ]),
            CourseGradeWeight::COMPONENT_UTS => $this->assignmentComponent($student, $assignments, Assignment::TYPE_UTS, $weights[CourseGradeWeight::COMPONENT_UTS]),
            CourseGradeWeight::COMPONENT_UAS => $this->assignmentComponent($student, $assignments, Assignment::TYPE_UAS, $weights[CourseGradeWeight::COMPONENT_UAS]),
        ];
        $rawScore = collect($components)->sum('contribution');
        $roundedScore = (int) round($rawScore);

        return [
            'student' => $student,
            'components' => $components,
            'raw_score' => round($rawScore, 2),
            'final_score' => $roundedScore,
            'letter_grade' => $this->letterForScore($roundedScore),
        ];
    }

    private function attendanceComponent(Student $student, Collection $attendances, int $weight): array
    {
        $total = $attendances->count();
        $earned = $attendances
            ->filter(function ($attendance) use ($student) {
                $record = $attendance->attendanceStudents->firstWhere('student_id', $student->id);

                return $record?->filled_at !== null || $record?->status === 'present';
            })
            ->count();
        $average = $total === 0 ? 0.0 : ($earned / $total) * 100;

        return $this->componentPayload($average, $weight, $earned, $total);
    }

    private function assignmentComponent(Student $student, Collection $assignments, string $assignmentType, int $weight): array
    {
        $typedAssignments = $assignments->where('assignment_type', $assignmentType)->values();
        $total = $typedAssignments->count();
        $earned = $typedAssignments->sum(function (Assignment $assignment) use ($student) {
            $submission = $assignment->submissions->firstWhere('student_id', $student->id);

            return $submission?->grade ?? 0;
        });
        $average = $total === 0 ? 0.0 : $earned / $total;

        return $this->componentPayload($average, $weight, $earned, $total);
    }

    private function componentPayload(float $average, int $weight, int|float $earned, int $total): array
    {
        return [
            'average' => round($average, 2),
            'weight' => $weight,
            'contribution' => round($average * $weight / 100, 2),
            'earned' => round($earned, 2),
            'total' => $total,
        ];
    }

    private function letterForScore(int $score): string
    {
        return match (true) {
            $score >= 85 => 'A',
            $score >= 74 => 'AB',
            $score >= 63 => 'B',
            $score >= 52 => 'BC',
            $score >= 41 => 'C',
            $score >= 30 => 'D',
            default => 'E',
        };
    }

    private function emptyWeights(): array
    {
        return [
            CourseGradeWeight::COMPONENT_ATTENDANCE => 0,
            CourseGradeWeight::COMPONENT_TUGAS => 0,
            CourseGradeWeight::COMPONENT_QUIZ => 0,
            CourseGradeWeight::COMPONENT_UTS => 0,
            CourseGradeWeight::COMPONENT_UAS => 0,
        ];
    }
}
