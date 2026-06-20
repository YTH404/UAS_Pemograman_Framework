@extends('layouts.admin')

@section('title', 'Add Course')

@section('header-title')
    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-sky-700">Add Course</p>
@endsection

@section('content')
    <section class="space-y-2">
        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">{{ $class->class_code }}</p>
        <h2 class="text-3xl font-semibold tracking-tight text-slate-950">Create a course for {{ $class->class_name }}</h2>
        <p class="text-sm text-slate-600">Set the course name and select a teacher for this class.</p>
    </section>

    <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/70">
        <form method="POST" action="{{ route('admin.manage-class.course.store', $class->id) }}" class="space-y-6">
            @csrf

            <div class="grid gap-6 lg:grid-cols-2">
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-slate-700">Course name</span>
                    <input type="text" name="course_name" value="{{ old('course_name') }}" placeholder="Pengantar Pemrograman" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:bg-white" />
                    @error('course_name')
                        <span class="mt-1 text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-slate-700">Teacher</span>
                    <select name="teacher_id" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white">
                        <option value="">Select teacher</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}" @selected(old('teacher_id') == $teacher->id)>{{ $teacher->name }} ({{ $teacher->username }})</option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                        <span class="mt-1 text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </label>
            </div>

            <div class="flex flex-wrap items-center justify-end gap-3">
                <a href="{{ route('admin.manage-class.course.index', $class->id) }}" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Cancel</a>
                <button type="submit" class="rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/20 transition hover:-translate-y-0.5 hover:bg-slate-800">Save Course</button>
            </div>
        </form>
    </section>
@endsection
