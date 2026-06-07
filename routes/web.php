<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Student\AssignmentController as StudentAssignmentController;
use App\Http\Controllers\Student\AttendanceController as StudentAttendanceController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\DoneMarkController as StudentDoneMarkController;
use App\Http\Controllers\Teacher\AssignmentController as TeacherAssignmentController;
use App\Http\Controllers\Teacher\AssignmentGradeController as TeacherAssignmentGradeController;
use App\Http\Controllers\Teacher\AttendanceController as TeacherAttendanceController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\LearningMaterialController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::middleware(['role:student'])->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/course/{course}', [StudentDashboardController::class, 'showCourse'])->name('student.course.show');
        Route::post('/dashboard/course/{course}/attendances/{attendance}', [StudentAttendanceController::class, 'fill'])->name('student.course.attendances.fill');
        Route::post('/dashboard/course/{course}/assignments/{assignment}', [StudentAssignmentController::class, 'submit'])->name('student.course.assignments.submit');
        Route::patch('/dashboard/course/{course}/done-marks/{doneMark}', [StudentDoneMarkController::class, 'toggle'])->name('student.course.done-marks.toggle');
    });

    Route::middleware(['role:teacher'])->group(function () {
        Route::get('/dashboard-teacher', [TeacherDashboardController::class, 'index'])->name('teacher.dashboard');
        Route::get('/dashboard-teacher/course/{course}', [TeacherDashboardController::class, 'showCourse'])->name('teacher.course.show');
        Route::post('/dashboard-teacher/course/{course}/attendances', [TeacherAttendanceController::class, 'store'])->name('teacher.course.attendances.store');
        Route::put('/dashboard-teacher/course/{course}/attendances/{attendance}', [TeacherAttendanceController::class, 'update'])->name('teacher.course.attendances.update');
        Route::post('/dashboard-teacher/course/{course}/assignments', [TeacherAssignmentController::class, 'store'])->name('teacher.course.assignments.store');
        Route::put('/dashboard-teacher/course/{course}/assignments/{assignment}', [TeacherAssignmentController::class, 'update'])->name('teacher.course.assignments.update');
        Route::get('/dashboard-teacher/course/{course}/assignments/{assignment}/grades', [TeacherAssignmentGradeController::class, 'index'])->name('teacher.course.assignments.grades.index');
        Route::patch('/dashboard-teacher/course/{course}/assignments/{assignment}/submissions/{submission}/grade', [TeacherAssignmentGradeController::class, 'update'])->name('teacher.course.assignments.submissions.grade');
        Route::post('/dashboard-teacher/course/{course}/materials', [LearningMaterialController::class, 'store'])->name('teacher.course.materials.store');
        Route::put('/dashboard-teacher/course/{course}/materials/{material}', [LearningMaterialController::class, 'update'])->name('teacher.course.materials.update');
        Route::delete('/dashboard-teacher/course/{course}/materials/{material}', [LearningMaterialController::class, 'destroy'])->name('teacher.course.materials.destroy');
    });

    Route::prefix('admin')->middleware(['role:admin'])->name('admin.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/class', [ClassController::class, 'index'])->name('class.index');
        Route::get('/class/create', [ClassController::class, 'create'])->name('class.create');
        Route::post('/class', [ClassController::class, 'store'])->name('class.store');
        Route::get('/class/{class}/edit', [ClassController::class, 'edit'])->name('class.edit');
        Route::put('/class/{class}', [ClassController::class, 'update'])->name('class.update');
        Route::delete('/class/{class}', [ClassController::class, 'destroy'])->name('class.destroy');
        Route::prefix('/class/{class}/manage')->name('manage-class.')->group(function () {
            Route::get('/', [ClassController::class, 'manage'])->name('index');
            Route::get('/student', [ClassController::class, 'students'])->name('student.index');

            Route::prefix('/course')->name('course.')->group(function () {
                Route::get('/', [CourseController::class, 'index'])->name('index');
                Route::get('/create', [CourseController::class, 'create'])->name('create');
                Route::post('/', [CourseController::class, 'store'])->name('store');
                Route::get('/{course}/edit', [CourseController::class, 'edit'])->name('edit');
                Route::put('/{course}', [CourseController::class, 'update'])->name('update');
                Route::delete('/{course}', [CourseController::class, 'destroy'])->name('destroy');
            });
        });
        
        Route::get('/teacher', [TeacherController::class, 'index'])->name('teacher.index');
        Route::get('/teacher/create', [TeacherController::class, 'create'])->name('teacher.create');
        Route::post('/teacher', [TeacherController::class, 'store'])->name('teacher.store');
        Route::get('/teacher/{teacher}/edit', [TeacherController::class, 'edit'])->name('teacher.edit');
        Route::put('/teacher/{teacher}', [TeacherController::class, 'update'])->name('teacher.update');
        Route::delete('/teacher/{teacher}', [TeacherController::class, 'destroy'])->name('teacher.destroy');

        Route::get('/student', [StudentController::class, 'index'])->name('student.index');
        Route::get('/student/create', [StudentController::class, 'create'])->name('student.create');
        Route::post('/student', [StudentController::class, 'store'])->name('student.store');
        Route::get('/student/{student}/edit', [StudentController::class, 'edit'])->name('student.edit');
        Route::put('/student/{student}', [StudentController::class, 'update'])->name('student.update');
        Route::delete('/student/{student}', [StudentController::class, 'destroy'])->name('student.destroy');
    });

    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
});
