@extends('layouts.admin')

@section('title', 'Manage Classes')

@section('header-title')
    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-sky-700">Class Management</p>
@endsection

@section('content')
    @php
        $classes = [
            ['id' => 1, 'name' => 'Class A - Morning Batch', 'level' => 'Intermediate', 'students' => 28, 'instructor' => 'Dr. Ahmad Wijaya', 'status' => 'Active'],
            ['id' => 2, 'name' => 'Class B - Afternoon Batch', 'level' => 'Beginner', 'students' => 31, 'instructor' => 'Dina Rahma', 'status' => 'Active'],
            ['id' => 3, 'name' => 'Class C - Evening Batch', 'level' => 'Advanced', 'students' => 25, 'instructor' => 'Budi Santoso', 'status' => 'Active'],
            ['id' => 4, 'name' => 'Class D - Weekend Session', 'level' => 'Intermediate', 'students' => 29, 'instructor' => 'Siti Nurhaliza', 'status' => 'Inactive'],
        ];
    @endphp

    <section class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950">Organize learning groups and classes</h2>
            </div>
            <button class="inline-flex items-center gap-2 rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-slate-900/20">
                <span>+</span> Add Class
            </button>
        </div>
    </section>

    <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl shadow-slate-200/70">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">Class Name</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">Level</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">Students</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">Instructor</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach ($classes as $class)
                        <tr class="transition-colors hover:bg-slate-50">
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $class['name'] }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $class['level'] }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-900">{{ $class['students'] }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $class['instructor'] }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $class['status'] === 'Active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                    {{ $class['status'] }}
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
