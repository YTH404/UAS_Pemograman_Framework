@extends('layouts.admin')

@section('title', 'Kelola Kelas')

@section('header-title')
    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-sky-700">Kelola Kelas</p>
@endsection

@section('content')
    <section class="space-y-2">
        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">{{ $class->class_code }}</p>
        <h2 class="text-3xl font-semibold tracking-tight text-slate-950">Kelola {{ $class->class_name }}</h2>
        <p class="text-sm text-slate-600">Pilih data kelas yang ingin ditinjau atau diperbarui.</p>
    </section>

    <section class="grid gap-5 lg:grid-cols-2">
        <a href="{{ route('admin.manage-class.course.index', $class->id) }}" class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/70 transition hover:-translate-y-0.5 hover:shadow-2xl hover:shadow-slate-200">
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-700">Mata Kuliah</p>
            <h3 class="mt-3 text-2xl font-semibold tracking-tight text-slate-950">Mata Kuliah</h3>
            <p class="mt-2 text-sm text-slate-600">{{ $class->courses_count }} mata kuliah terhubung ke kelas ini.</p>
        </a>

        <a href="{{ route('admin.manage-class.student.index', $class->id) }}" class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/70 transition hover:-translate-y-0.5 hover:shadow-2xl hover:shadow-slate-200">
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-sky-700">Mahasiswa</p>
            <h3 class="mt-3 text-2xl font-semibold tracking-tight text-slate-950">Mahasiswa</h3>
            <p class="mt-2 text-sm text-slate-600">{{ $class->student_classes_count }} mahasiswa mengikuti kelas ini.</p>
        </a>
    </section>

    <div class="flex justify-end">
        <a href="{{ route('admin.class.index') }}" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Kembali ke Kelas</a>
    </div>
@endsection
