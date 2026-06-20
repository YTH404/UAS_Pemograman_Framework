@extends('layouts.admin')

@section('title', 'Class Students')

@section('header-title')
    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-sky-700">Class Students</p>
@endsection

@section('content')
    <section class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">{{ $class->class_code }}</p>
                <h2 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950">Students enrolled in {{ $class->class_name }}</h2>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.manage-class.index', $class->id) }}" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Back to Options</a>
                <a href="{{ route('admin.class.index') }}" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Back to Classes</a>
            </div>
        </div>
    </section>

    <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl shadow-slate-200/70">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">Name</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">Username</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">Class</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($studentClasses as $studentClass)
                        @if ($studentClass->student)
                            <tr class="transition-colors hover:bg-slate-50">
                                <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $studentClass->student->name }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $studentClass->student->username }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $class->class_name }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.student.edit', $studentClass->student->id) }}" class="rounded-lg px-3 py-2 text-xs font-semibold text-sky-700 transition-colors hover:bg-sky-100">Edit</a>
                                        <form method="POST" action="{{ route('admin.student.destroy', $studentClass->student->id) }}" data-swal-delete data-swal-title="{{ __('sweetalert.delete.student.title') }}" data-swal-text="{{ __('sweetalert.delete.student.text', ['name' => $studentClass->student->name]) }}" data-swal-confirm="{{ __('sweetalert.delete.student.confirm') }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg px-3 py-2 text-xs font-semibold text-red-600 transition-colors hover:bg-red-100">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-500">No students are enrolled in this class yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
