<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/login', 'auth.login')->name('login');
Route::view('/dashboard', 'dashboard')->name('dashboard');
Route::view('/teacher-dashboard', 'teacher-dashboard')->name('teacher.dashboard');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::view('/', 'admin.dashboard')->name('dashboard');
    Route::view('/manage-teacher', 'admin.manage-teacher')->name('manage-teacher');
    Route::view('/manage-class', 'admin.manage-class')->name('manage-class');
});
