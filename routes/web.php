<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ClassController;
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
        Route::get('/manage-class', [ClassController::class, 'index'])->name('manage-class');
        Route::get('/manage-class/create', [ClassController::class, 'create'])->name('class.create');
        Route::post('/manage-class', [ClassController::class, 'store'])->name('manage-class.store');
        Route::get('/manage-class/{class}/edit', [ClassController::class, 'edit'])->name('class.edit');
        Route::put('/manage-class/{class}', [ClassController::class, 'update'])->name('class.update');
        Route::delete('/manage-class/{class}', [ClassController::class, 'destroy'])->name('class.destroy');
        
        Route::view('/manage-teacher', 'admin.manage-teacher')->name('manage-teacher');
    });

    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
});


