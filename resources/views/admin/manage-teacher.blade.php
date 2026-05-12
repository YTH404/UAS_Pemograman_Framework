@extends('layouts.admin')

@section('title', 'Manage Teachers')

@section('header-title')
    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-sky-700">Teacher Management</p>
@endsection

@section('content')
    @php
        $teachers = [
            ['id' => 1, 'name' => 'Dr. Ahmad Wijaya', 'email' => 'ahmad@lms.local', 'specialization' => 'Web Development', 'courses' => 4, 'status' => 'Active'],
            ['id' => 2, 'name' => 'Dina Rahma', 'email' => 'dina@lms.local', 'specialization' => 'Database Design', 'courses' => 3, 'status' => 'Active'],
            ['id' => 3, 'name' => 'Budi Santoso', 'email' => 'budi@lms.local', 'specialization' => 'UI/UX Design', 'courses' => 2, 'status' => 'Active'],
            ['id' => 4, 'name' => 'Siti Nurhaliza', 'email' => 'siti@lms.local', 'specialization' => 'Software Engineering', 'courses' => 5, 'status' => 'Inactive'],
        ];
    @endphp

    <section class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950">Manage instructors in the system</h2>
            </div>
            <button class="inline-flex items-center gap-2 rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-slate-900/20">
                <span>+</span> Add Teacher
            </button>
        </div>
    </section>

    <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl shadow-slate-200/70">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">Name</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">Email</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">Specialization</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">Courses</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach ($teachers as $teacher)
                        <tr class="transition-colors hover:bg-slate-50">
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $teacher['name'] }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $teacher['email'] }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $teacher['specialization'] }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-900">{{ $teacher['courses'] }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $teacher['status'] === 'Active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                    {{ $teacher['status'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex gap-2">
                                    <button class="rounded-lg px-3 py-2 text-xs font-semibold text-sky-700 transition-colors hover:bg-sky-100">Edit</button>
                                    <button class="rounded-lg px-3 py-2 text-xs font-semibold text-red-700 transition-colors hover:bg-red-100">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
