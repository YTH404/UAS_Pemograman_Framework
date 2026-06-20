<?php

use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\AttendanceStudent;
use App\Models\Classes as CourseClass;
use App\Models\Course;
use App\Models\CourseGradeWeight;
use App\Models\StudentClass;
use App\Models\Submission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

afterEach(function () {
    Carbon::setTestNow();
});

function gradebookUser(string $role, string $name): User
{
    return User::create([
        'name' => $name,
        'username' => Str::slug($role . '-' . $name . '-' . Str::random(6)),
        'password' => 'password',
        'role' => $role,
    ]);
}

function gradebookCourse(User $teacher): array
{
    $class = CourseClass::create([
        'class_name' => 'Framework A',
        'class_code' => 'FW-A-' . Str::random(4),
    ]);

    $course = Course::create([
        'class_id' => $class->id,
        'teacher_id' => $teacher->id,
        'course_name' => 'Pemograman Framework',
    ]);

    return [$class, $course];
}

function lockGradeWeights(Course $course, array $weights = []): CourseGradeWeight
{
    return CourseGradeWeight::create([
        'course_id' => $course->id,
        'attendance_weight' => $weights['attendance_weight'] ?? 20,
        'tugas_weight' => $weights['tugas_weight'] ?? 20,
        'quiz_weight' => $weights['quiz_weight'] ?? 20,
        'uts_weight' => $weights['uts_weight'] ?? 20,
        'uas_weight' => $weights['uas_weight'] ?? 20,
        'locked_at' => now(),
    ]);
}

test('teacher can lock grade weights totaling exactly one hundred with zero values', function () {
    $teacher = gradebookUser('teacher', 'Teacher One');
    [, $course] = gradebookCourse($teacher);

    $this->actingAs($teacher)
        ->post(route('teacher.course.grade-weights.store', $course->id), [
            'attendance_weight' => 0,
            'tugas_weight' => 50,
            'quiz_weight' => 0,
            'uts_weight' => 25,
            'uas_weight' => 25,
        ])
        ->assertRedirect(route('teacher.course.show', $course->id))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('course_grade_weights', [
        'course_id' => $course->id,
        'attendance_weight' => 0,
        'tugas_weight' => 50,
        'quiz_weight' => 0,
        'uts_weight' => 25,
        'uas_weight' => 25,
    ]);
});

test('grade weights must total one hundred and cannot be saved twice', function () {
    $teacher = gradebookUser('teacher', 'Teacher One');
    [, $course] = gradebookCourse($teacher);

    $this->actingAs($teacher)
        ->post(route('teacher.course.grade-weights.store', $course->id), [
            'attendance_weight' => 10,
            'tugas_weight' => 20,
            'quiz_weight' => 20,
            'uts_weight' => 20,
            'uas_weight' => 20,
        ])
        ->assertSessionHas('error');

    $this->assertDatabaseMissing('course_grade_weights', [
        'course_id' => $course->id,
    ]);

    lockGradeWeights($course);

    $this->actingAs($teacher)
        ->post(route('teacher.course.grade-weights.store', $course->id), [
            'attendance_weight' => 0,
            'tugas_weight' => 0,
            'quiz_weight' => 0,
            'uts_weight' => 0,
            'uas_weight' => 100,
        ])
        ->assertSessionHas('error');
});

test('assignment creation requires locked weights and enforces exam type weeks', function () {
    $teacher = gradebookUser('teacher', 'Teacher One');
    [, $course] = gradebookCourse($teacher);

    $assignmentPayload = [
        'meeting' => 'Pertemuan 1',
        'assignment_type' => Assignment::TYPE_TUGAS,
        'title' => 'Task One',
        'description' => null,
        'started_at' => '2026-06-20 08:00:00',
        'ended_at' => '2026-06-20 09:00:00',
    ];

    $this->actingAs($teacher)
        ->post(route('teacher.course.assignments.store', $course->id), $assignmentPayload)
        ->assertSessionHas('error');

    lockGradeWeights($course);

    $this->actingAs($teacher)
        ->post(route('teacher.course.assignments.store', $course->id), $assignmentPayload)
        ->assertRedirect(route('teacher.course.show', $course->id))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('assignments', [
        'course_id' => $course->id,
        'assignment_type' => Assignment::TYPE_TUGAS,
        'meeting' => 'Pertemuan 1',
    ]);

    $this->actingAs($teacher)
        ->post(route('teacher.course.assignments.store', $course->id), [
            ...$assignmentPayload,
            'meeting' => 'Pertemuan 2',
            'assignment_type' => Assignment::TYPE_QUIZ,
            'title' => 'Quiz One',
        ])
        ->assertSessionHas('success');

    $this->assertDatabaseHas('assignments', [
        'course_id' => $course->id,
        'assignment_type' => Assignment::TYPE_QUIZ,
        'meeting' => 'Pertemuan 2',
    ]);

    $this->actingAs($teacher)
        ->post(route('teacher.course.assignments.store', $course->id), [
            ...$assignmentPayload,
            'meeting' => 'Pertemuan 2',
            'assignment_type' => Assignment::TYPE_UTS,
            'title' => 'Wrong UTS',
        ])
        ->assertSessionHas('error');
});

test('course only allows one uts and one uas in their required weeks', function () {
    $teacher = gradebookUser('teacher', 'Teacher One');
    [, $course] = gradebookCourse($teacher);
    lockGradeWeights($course);

    foreach (range(1, 7) as $meeting) {
        Assignment::create([
            'course_id' => $course->id,
            'meeting' => 'Pertemuan ' . $meeting,
            'assignment_type' => Assignment::TYPE_TUGAS,
            'title' => 'Task ' . $meeting,
            'started_at' => '2026-06-20 08:00:00',
            'ended_at' => '2026-06-20 09:00:00',
        ]);
    }

    $this->actingAs($teacher)
        ->post(route('teacher.course.assignments.store', $course->id), [
            'meeting' => 'Pertemuan 8',
            'assignment_type' => Assignment::TYPE_UTS,
            'title' => 'Midterm',
            'description' => null,
            'started_at' => '2026-06-20 08:00:00',
            'ended_at' => '2026-06-20 09:00:00',
        ])
        ->assertSessionHas('success');

    $this->actingAs($teacher)
        ->post(route('teacher.course.assignments.store', $course->id), [
            'meeting' => 'Pertemuan 8',
            'assignment_type' => Assignment::TYPE_UTS,
            'title' => 'Duplicate Midterm',
            'description' => null,
            'started_at' => '2026-06-20 10:00:00',
            'ended_at' => '2026-06-20 11:00:00',
        ])
        ->assertSessionHas('error');
});

test('gradebook hides final grades before uas and shows calculated letters after uas exists', function () {
    Carbon::setTestNow(Carbon::parse('2026-06-20 10:00:00'));

    $teacher = gradebookUser('teacher', 'Teacher One');
    [$class, $course] = gradebookCourse($teacher);
    $student = gradebookUser('student', 'Student One');

    StudentClass::create([
        'student_id' => $student->id,
        'class_id' => $class->id,
    ]);

    lockGradeWeights($course, [
        'attendance_weight' => 20,
        'tugas_weight' => 20,
        'quiz_weight' => 20,
        'uts_weight' => 20,
        'uas_weight' => 20,
    ]);

    $attendance = Attendance::create([
        'course_id' => $course->id,
        'meeting' => 'Pertemuan 1',
        'title' => 'Attendance 1',
        'started_at' => '2026-06-20 08:00:00',
        'ended_at' => '2026-06-20 09:00:00',
    ]);

    AttendanceStudent::create([
        'attendance_id' => $attendance->id,
        'student_id' => $student->id,
        'status' => 'present',
        'filled_at' => '2026-06-20 08:30:00',
    ]);

    $tugas = Assignment::create([
        'course_id' => $course->id,
        'meeting' => 'Pertemuan 1',
        'assignment_type' => Assignment::TYPE_TUGAS,
        'title' => 'Task',
        'started_at' => '2026-06-20 08:00:00',
        'ended_at' => '2026-06-20 09:00:00',
    ]);

    Submission::create([
        'assignment_id' => $tugas->id,
        'student_id' => $student->id,
        'submitted_at' => '2026-06-20 08:30:00',
        'grade' => 90,
    ]);

    $this->actingAs($teacher)
        ->get(route('teacher.course.gradebook', $course->id))
        ->assertOk()
        ->assertSee('Pending UAS')
        ->assertSee('Final Grades')
        ->assertDontSee('AB');

    $uas = Assignment::create([
        'course_id' => $course->id,
        'meeting' => 'Pertemuan 16',
        'assignment_type' => Assignment::TYPE_UAS,
        'title' => 'Final Exam',
        'started_at' => '2026-06-20 08:00:00',
        'ended_at' => '2026-06-20 09:00:00',
    ]);

    Submission::create([
        'assignment_id' => $uas->id,
        'student_id' => $student->id,
        'submitted_at' => '2026-06-20 08:30:00',
        'grade' => 80,
    ]);

    $this->actingAs($teacher)
        ->get(route('teacher.course.gradebook', $course->id))
        ->assertOk()
        ->assertSee('54')
        ->assertSee('BC');
});

test('teacher cannot view another teachers gradebook and students cannot access it', function () {
    $teacher = gradebookUser('teacher', 'Teacher One');
    $otherTeacher = gradebookUser('teacher', 'Teacher Two');
    $student = gradebookUser('student', 'Student One');
    [, $course] = gradebookCourse($otherTeacher);

    $this->actingAs($teacher)
        ->get(route('teacher.course.gradebook', $course->id))
        ->assertNotFound();

    $this->actingAs($student)
        ->get(route('teacher.course.gradebook', $course->id))
        ->assertForbidden();
});
