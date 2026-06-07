@extends('layouts.admin')

@section('title', 'Kelola Mata Kuliah Kelas')

@section('header-title')
    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-sky-700">Manajemen Mata Kuliah</p>
@endsection

@section('content')
    <section class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">{{ $class->class_code }}</p>
                <h2 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950">Kelola mata kuliah untuk {{ $class->class_name }}</h2>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.manage-class.index', $class->id) }}" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Kembali ke Opsi</a>
                <a href="{{ route('admin.class.index') }}" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Kembali ke Kelas</a>
                <a href="{{ route('admin.manage-class.course.create', $class->id) }}" class="inline-flex items-center gap-2 rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-slate-900/20">
                    <span>+</span> Tambah Mata Kuliah
                </a>
            </div>
        </div>
    </section>

    <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl shadow-slate-200/70">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">Nama Mata Kuliah</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">Dosen</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($courses as $course)
                        <tr class="transition-colors hover:bg-slate-50">
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $course->course_name }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $course->teacher?->name ?? 'Belum ada dosen' }}</td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.manage-class.course.edit', [$class->id, $course->id]) }}" class="rounded-lg px-3 py-2 text-xs font-semibold text-sky-700 transition-colors hover:bg-sky-100">Ubah</a>
                                    <form method="POST" action="{{ route('admin.manage-class.course.destroy', [$class->id, $course->id]) }}" data-swal-delete data-swal-title="{{ __('sweetalert.delete.course.title') }}" data-swal-text="{{ __('sweetalert.delete.course.text', ['name' => $course->course_name]) }}" data-swal-confirm="{{ __('sweetalert.delete.course.confirm') }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg px-3 py-2 text-xs font-semibold text-red-600 transition-colors hover:bg-red-100">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-sm text-slate-500">Belum ada mata kuliah untuk kelas ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
