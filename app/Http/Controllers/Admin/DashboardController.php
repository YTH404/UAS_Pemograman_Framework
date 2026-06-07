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
            ['label' => 'Total Dosen', 'value' => $teacherCount, 'note' => 'Akun dosen aktif'],
            ['label' => 'Total Kelas', 'value' => $classCount, 'note' => 'Kelompok belajar aktif'],
            ['label' => 'Total Mahasiswa', 'value' => $studentCount, 'note' => 'Akun mahasiswa terdaftar'],
            ['label' => 'Total Mata Kuliah', 'value' => $courseCount, 'note' => 'Mata kuliah yang terhubung ke kelas'],
        ];

        $activityStats = [
            ['label' => 'Materi', 'value' => $learningMaterialCount, 'note' => 'Dokumen, video, dan tautan yang diunggah'],
            ['label' => 'Tugas', 'value' => $assignmentCount, 'note' => 'Jadwal tugas yang dibuat dosen'],
            ['label' => 'Presensi', 'value' => $attendanceCount, 'note' => 'Sesi presensi yang dibuat dosen'],
            ['label' => 'File Terkumpul', 'value' => $submissionCount . '/' . $totalSubmissionSlots, 'note' => 'Slot pengumpulan tugas yang sudah dikirim'],
        ];

        $recentCourses = Course::with(['classes', 'teacher'])
            ->withCount(['learningMaterials', 'assignments', 'attendances'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('dashboardStats', 'activityStats', 'recentCourses'));
    }
}
