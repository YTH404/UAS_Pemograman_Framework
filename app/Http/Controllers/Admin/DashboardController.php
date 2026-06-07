<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\Classes;
use App\Models\Course;
use App\Models\LearningMaterial;
use App\Models\Student;
use App\Models\Submission;
use App\Models\Teacher;

class DashboardController extends Controller
{
    public function index()
    {
        $teacherCount = Teacher::count();
        $classCount = Classes::count();
        $studentCount = Student::count();
        $courseCount = Course::count();
        $assignmentCount = Assignment::count();
        $submissionCount = Submission::whereNotNull('submitted_at')->count();
        $totalSubmissionSlots = Submission::count();
        $attendanceCount = Attendance::count();
        $learningMaterialCount = LearningMaterial::count();

        $dashboardStats = [
            ['label' => 'Total Teachers', 'value' => $teacherCount, 'note' => 'Active instructor accounts'],
            ['label' => 'Total Classes', 'value' => $classCount, 'note' => 'Active learning groups'],
            ['label' => 'Total Students', 'value' => $studentCount, 'note' => 'Enrolled student accounts'],
            ['label' => 'Total Courses', 'value' => $courseCount, 'note' => 'Courses assigned to classes'],
        ];

        $activityStats = [
            ['label' => 'Learning Materials', 'value' => $learningMaterialCount, 'note' => 'Uploaded documents, videos, and links'],
            ['label' => 'Assignments', 'value' => $assignmentCount, 'note' => 'Assignment windows created by teachers'],
            ['label' => 'Attendances', 'value' => $attendanceCount, 'note' => 'Attendance sessions created by teachers'],
            ['label' => 'Submitted Files', 'value' => $submissionCount . '/' . $totalSubmissionSlots, 'note' => 'Submitted assignment slots'],
        ];

        $recentCourses = Course::with(['classes', 'teacher'])
            ->withCount(['learningMaterials', 'assignments', 'attendances'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('dashboardStats', 'activityStats', 'recentCourses'));
    }
}
