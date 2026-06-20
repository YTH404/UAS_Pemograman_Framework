<?php

use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\AttendanceStudent;
use App\Models\Classes as CourseClass;
use App\Models\Course;
use App\Models\StudentClass;
use App\Models\Submission;
use App\Models\SubmissionFile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

afterEach(function () {
    Carbon::setTestNow();
});

function reportUser(string $role, string $name): User
{
    return User::create([
        'name' => $name,
        'username' => Str::slug($role . '-' . $name . '-' . Str::random(6)),
        'password' => 'password',
        'role' => $role,
    ]);
}

function reportCourse(User $teacher): array
{
    $class = CourseClass::create([
        'class_name' => 'Framework A',
        'class_code' => 'FW-A',
    ]);

    $course = Course::create([
        'class_id' => $class->id,
        'teacher_id' => $teacher->id,
        'course_name' => 'Pemograman Framework',
    ]);

    return [$class, $course];
}

test('teacher can open the report for their own course and see current students without activity', function () {
    $teacher = reportUser('teacher', 'Teacher One');
    [$class, $course] = reportCourse($teacher);
    $student = reportUser('student', 'Student Empty');

    StudentClass::create([
        'student_id' => $student->id,
        'class_id' => $class->id,
    ]);

    $this->actingAs($teacher)
        ->get(route('teacher.course.students.report', $course->id))
        ->assertOk()
        ->assertSee('Student Activity Report')
        ->assertSee('Choose Student')
        ->assertSee('Student Empty')
        ->assertSee('No attendance has been created for this course.')
        ->assertSee('No assignments have been created for this course.');
});

test('teacher cannot open another teachers course report', function () {
    $teacher = reportUser('teacher', 'Teacher One');
    $otherTeacher = reportUser('teacher', 'Teacher Two');
    [, $course] = reportCourse($otherTeacher);

    $this->actingAs($teacher)
        ->get(route('teacher.course.students.report', $course->id))
        ->assertNotFound();
});

test('teacher can choose one current student to observe', function () {
    Carbon::setTestNow(Carbon::parse('2026-06-20 10:00:00'));

    $teacher = reportUser('teacher', 'Teacher One');
    [$class, $course] = reportCourse($teacher);
    $firstStudent = reportUser('student', 'Student Alpha');
    $secondStudent = reportUser('student', 'Student Beta');

    StudentClass::create([
        'student_id' => $firstStudent->id,
        'class_id' => $class->id,
    ]);

    StudentClass::create([
        'student_id' => $secondStudent->id,
        'class_id' => $class->id,
    ]);

    $attendance = Attendance::create([
        'course_id' => $course->id,
        'meeting' => 'Pertemuan 1',
        'title' => 'Closed Attendance',
        'started_at' => Carbon::parse('2026-06-19 08:00:00'),
        'ended_at' => Carbon::parse('2026-06-19 09:00:00'),
    ]);

    AttendanceStudent::create([
        'attendance_id' => $attendance->id,
        'student_id' => $secondStudent->id,
        'status' => 'present',
        'filled_at' => Carbon::parse('2026-06-19 08:30:00'),
    ]);

    $this->actingAs($teacher)
        ->get(route('teacher.course.students.report', $course->id))
        ->assertOk()
        ->assertSee('Student Alpha')
        ->assertSee('Absent')
        ->assertDontSee('Present');

    $this->actingAs($teacher)
        ->get(route('teacher.course.students.report', ['course' => $course->id, 'student_id' => $secondStudent->id]))
        ->assertOk()
        ->assertSee('Student Beta')
        ->assertSee('Present')
        ->assertDontSee('Absent');
});

test('report renders attendance statuses submission statuses and submitted file links', function () {
    Carbon::setTestNow(Carbon::parse('2026-06-20 10:00:00'));

    $teacher = reportUser('teacher', 'Teacher One');
    [$class, $course] = reportCourse($teacher);
    $student = reportUser('student', 'Student Active');

    StudentClass::create([
        'student_id' => $student->id,
        'class_id' => $class->id,
    ]);

    $presentAttendance = Attendance::create([
        'course_id' => $course->id,
        'meeting' => 'Pertemuan 1',
        'title' => 'Presence Check',
        'started_at' => Carbon::parse('2026-06-20 08:00:00'),
        'ended_at' => Carbon::parse('2026-06-20 09:00:00'),
    ]);

    AttendanceStudent::create([
        'attendance_id' => $presentAttendance->id,
        'student_id' => $student->id,
        'status' => 'present',
        'filled_at' => Carbon::parse('2026-06-20 08:30:00'),
    ]);

    Attendance::create([
        'course_id' => $course->id,
        'meeting' => 'Pertemuan 2',
        'title' => 'Open Attendance',
        'started_at' => Carbon::parse('2026-06-20 09:00:00'),
        'ended_at' => Carbon::parse('2026-06-20 11:00:00'),
    ]);

    Attendance::create([
        'course_id' => $course->id,
        'meeting' => 'Pertemuan 3',
        'title' => 'Future Attendance',
        'started_at' => Carbon::parse('2026-06-20 12:00:00'),
        'ended_at' => Carbon::parse('2026-06-20 13:00:00'),
    ]);

    Attendance::create([
        'course_id' => $course->id,
        'meeting' => 'Pertemuan 4',
        'title' => 'Closed Attendance',
        'started_at' => Carbon::parse('2026-06-19 08:00:00'),
        'ended_at' => Carbon::parse('2026-06-19 09:00:00'),
    ]);

    $gradedAssignment = Assignment::create([
        'course_id' => $course->id,
        'meeting' => 'Pertemuan 1',
        'title' => 'Graded Assignment',
        'description' => 'Upload the graded work.',
        'started_at' => Carbon::parse('2026-06-20 08:00:00'),
        'ended_at' => Carbon::parse('2026-06-20 09:00:00'),
    ]);

    $gradedSubmission = Submission::create([
        'assignment_id' => $gradedAssignment->id,
        'student_id' => $student->id,
        'submitted_at' => Carbon::parse('2026-06-20 08:40:00'),
        'grade' => 95,
    ]);

    SubmissionFile::create([
        'submission_id' => $gradedSubmission->id,
        'file_path' => 'submissions/final-answer.pdf',
        'original_name' => 'final-answer.pdf',
        'mime_type' => 'application/pdf',
        'file_size' => 1200,
    ]);

    $submittedAssignment = Assignment::create([
        'course_id' => $course->id,
        'meeting' => 'Pertemuan 2',
        'title' => 'Submitted Assignment',
        'description' => null,
        'started_at' => Carbon::parse('2026-06-20 08:00:00'),
        'ended_at' => Carbon::parse('2026-06-20 11:00:00'),
    ]);

    Submission::create([
        'assignment_id' => $submittedAssignment->id,
        'student_id' => $student->id,
        'submitted_at' => Carbon::parse('2026-06-20 09:30:00'),
        'grade' => null,
    ]);

    Assignment::create([
        'course_id' => $course->id,
        'meeting' => 'Pertemuan 3',
        'title' => 'Open Missing Assignment',
        'description' => null,
        'started_at' => Carbon::parse('2026-06-20 09:00:00'),
        'ended_at' => Carbon::parse('2026-06-20 11:00:00'),
    ]);

    Assignment::create([
        'course_id' => $course->id,
        'meeting' => 'Pertemuan 4',
        'title' => 'Future Assignment',
        'description' => null,
        'started_at' => Carbon::parse('2026-06-20 12:00:00'),
        'ended_at' => Carbon::parse('2026-06-20 13:00:00'),
    ]);

    $closedAssignment = Assignment::create([
        'course_id' => $course->id,
        'meeting' => 'Pertemuan 5',
        'title' => 'Closed Assignment',
        'description' => null,
        'started_at' => Carbon::parse('2026-06-19 08:00:00'),
        'ended_at' => Carbon::parse('2026-06-19 09:00:00'),
    ]);

    Submission::create([
        'assignment_id' => $closedAssignment->id,
        'student_id' => $student->id,
        'submitted_at' => null,
        'grade' => null,
    ]);

    $this->actingAs($teacher)
        ->get(route('teacher.course.students.report', $course->id))
        ->assertOk()
        ->assertSee('Present')
        ->assertSee('Pending')
        ->assertSee('Absent')
        ->assertSee('Graded')
        ->assertSee('Submitted')
        ->assertSee('Missing')
        ->assertSee('Not open yet')
        ->assertSee('Closed')
        ->assertSee('final-answer.pdf')
        ->assertSee('href=', false);
});
