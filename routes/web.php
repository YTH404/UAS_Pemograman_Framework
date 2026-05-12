<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/login', 'auth.login')->name('login');
Route::view('/dashboard', 'dashboard')->name('dashboard');
Route::view('/teacher-dashboard', 'teacher-dashboard')->name('teacher.dashboard');
