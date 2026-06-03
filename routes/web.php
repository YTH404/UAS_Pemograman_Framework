<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
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
    });

    Route::middleware(['role:teacher'])->group(function () {
        Route::get('/dashboard-teacher', [TeacherDashboardController::class, 'index'])->name('teacher.dashboard');
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
