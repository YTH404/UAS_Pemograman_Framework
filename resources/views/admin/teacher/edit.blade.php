@extends('layouts.admin')

@section('title', 'Edit Teacher')

@section('header-title')
    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-sky-700">Edit Teacher</p>
@endsection

@section('content')
    <section class="space-y-2">
        <h2 class="text-3xl font-semibold tracking-tight text-slate-950">Update teacher details</h2>
    </section>

    <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/70">
        <form method="POST" action="{{ route('admin.teacher.update', $teacher->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid gap-6 lg:grid-cols-2">
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-slate-700">Name</span>
                    <input type="text" name="name" value="{{ old('name', $teacher->name) }}" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:bg-white" />
                    @error('name')
                        <span class="mt-1 text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-slate-700">Username</span>
                    <input type="text" name="username" value="{{ old('username', $teacher->username) }}" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:bg-white" />
                    @error('username')
                        <span class="mt-1 text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-slate-700">New password</span>
                    <input type="password" name="password" placeholder="Empty if not changing" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:bg-white" />
                    @error('password')
                        <span class="mt-1 text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-slate-700">Confirm new password</span>
                    <input type="password" name="password_confirmation" placeholder="Empty if not changing" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:bg-white" />
                </label>
            </div>

            <div class="flex flex-wrap items-center justify-end gap-3">
                <a href="{{ route('admin.manage-teacher') }}" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Cancel</a>
                <button type="submit" class="rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/20 transition hover:-translate-y-0.5 hover:bg-slate-800">Update Teacher</button>
            </div>
        </form>
    </section>
@endsection
