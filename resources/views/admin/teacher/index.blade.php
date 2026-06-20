@extends('layouts.admin')

@section('title', 'Manage Teachers')

@section('header-title')
    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-sky-700">Teacher Management</p>
@endsection

@section('content')

    <section class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950">Manage teachers in the system</h2>
            </div>
            <a href="{{ route('admin.teacher.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-slate-900/20">
                <span>+</span> Add Teacher
            </a>
        </div>
    </section>

    <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl shadow-slate-200/70">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">Name</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">Username</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach ($teachers as $teacher)
                        <tr class="transition-colors hover:bg-slate-50">
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $teacher['name'] }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $teacher['username'] }}</td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.teacher.edit', $teacher['id']) }}" class="rounded-lg px-3 py-2 text-xs font-semibold text-sky-700 transition-colors hover:bg-sky-100">Edit</a>
                                    <form method="POST" action="{{ route('admin.teacher.destroy', $teacher['id']) }}" data-swal-delete data-swal-title="{{ __('sweetalert.delete.teacher.title') }}" data-swal-text="{{ __('sweetalert.delete.teacher.text', ['name' => $teacher['name']]) }}" data-swal-confirm="{{ __('sweetalert.delete.teacher.confirm') }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg px-3 py-2 text-xs font-semibold text-red-600 transition-colors hover:bg-red-100">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
