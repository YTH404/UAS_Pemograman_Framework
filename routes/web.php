<?php

use App\Http\Controllers\AuthController;
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
        Route::view('/dashboard', 'student.dashboard')->name('dashboard');
    });

    Route::middleware(['role:teacher'])->group(function () {
        Route::view('/teacher-dashboard', 'teacher.teacher-dashboard')->name('teacher.dashboard');
    });

    Route::prefix('admin')->middleware(['role:admin'])->name('admin.')->group(function () {
        Route::view('/', 'admin.dashboard')->name('dashboard');
        Route::view('/manage-teacher', 'admin.manage-teacher')->name('manage-teacher');
        Route::view('/manage-class', 'admin.manage-class')->name('manage-class');
    });

    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
});


