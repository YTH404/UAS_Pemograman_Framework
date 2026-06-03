@extends('layouts.admin')

@section('title', 'Manage Class')

@section('header-title')
    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-sky-700">Manage Class</p>
@endsection

@section('content')
    <section class="space-y-2">
        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">{{ $class->class_code }}</p>
        <h2 class="text-3xl font-semibold tracking-tight text-slate-950">Manage {{ $class->class_name }}</h2>
        <p class="text-sm text-slate-600">Choose which class data you want to review or update.</p>
    </section>

    <section class="grid gap-5 lg:grid-cols-2">
        <a href="{{ route('admin.manage-class.course.index', $class->id) }}" class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/70 transition hover:-translate-y-0.5 hover:shadow-2xl hover:shadow-slate-200">
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-700">Courses</p>
            <h3 class="mt-3 text-2xl font-semibold tracking-tight text-slate-950">Course</h3>
            <p class="mt-2 text-sm text-slate-600">{{ $class->courses_count }} courses assigned to this class.</p>
        </a>

        <a href="{{ route('admin.manage-class.student.index', $class->id) }}" class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/70 transition hover:-translate-y-0.5 hover:shadow-2xl hover:shadow-slate-200">
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-sky-700">Attendance</p>
            <h3 class="mt-3 text-2xl font-semibold tracking-tight text-slate-950">Student</h3>
            <p class="mt-2 text-sm text-slate-600">{{ $class->student_classes_count }} students attend this class.</p>
        </a>
    </section>

    <div class="flex justify-end">
        <a href="{{ route('admin.class.index') }}" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Back to Classes</a>
    </div>
@endsection
