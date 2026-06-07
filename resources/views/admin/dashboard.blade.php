@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('header-title')
    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-sky-700">Dashboard</p>
@endsection

@section('content')
    <section class="space-y-6">
        <article class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/70 lg:p-10">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-semibold tracking-tight text-slate-950">System Overview</h2>
                    <p class="mt-2 text-slate-600">Monitor key metrics and system activity</p>
                </div>
            </div>

            <div class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                @foreach ($dashboardStats as $stat)
                    <div class="rounded-3xl bg-slate-950 p-5 text-white shadow-sm ring-1 ring-white/10">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-sky-300">{{ $stat['label'] }}</p>
                        <p class="mt-4 text-4xl font-semibold">{{ $stat['value'] }}</p>
                        <p class="mt-3 text-sm leading-6 text-slate-300">{{ $stat['note'] }}</p>
                    </div>
                @endforeach
            </div>
        </article>
    </section>

    <div class="flex items-center gap-4">
        <div class="h-px flex-1 bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>
        <span class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Quick Actions</span>
        <div class="h-px flex-1 bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>
    </div>

    <section class="space-y-4">
        <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            <a href="{{ route('admin.teacher.index') }}" class="group relative overflow-hidden rounded-[1.75rem] border border-slate-200 bg-sky-50 p-6 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-200/70">
                <div class="absolute -right-6 -top-4 h-24 w-24 rounded-full bg-sky-200/70 opacity-60"></div>
                <div class="relative">
                    <h3 class="text-xl font-semibold text-slate-950">Manage Teachers</h3>
                    <p class="mt-2 text-sm text-slate-600">Add, edit, or remove instructor accounts</p>
                    <div class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-sky-700 group-hover:gap-3 transition-all">
                        View <span>→</span>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.class.index') }}" class="group relative overflow-hidden rounded-[1.75rem] border border-slate-200 bg-emerald-50 p-6 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-200/70">
                <div class="absolute -right-6 -top-4 h-24 w-24 rounded-full bg-emerald-200/70 opacity-60"></div>
                <div class="relative">
                    <h3 class="text-xl font-semibold text-slate-950">Manage Classes</h3>
                    <p class="mt-2 text-sm text-slate-600">Create, edit, or organize learning groups</p>
                    <div class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 group-hover:gap-3 transition-all">
                        View <span>→</span>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.student.index') }}" class="group relative overflow-hidden rounded-[1.75rem] border border-slate-200 bg-amber-50 p-6 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-200/70">
                <div class="absolute -right-6 -top-4 h-24 w-24 rounded-full bg-amber-200/70 opacity-60"></div>
                <div class="relative">
                    <h3 class="text-xl font-semibold text-slate-950">Manage Students</h3>
                    <p class="mt-2 text-sm text-slate-600">Add, edit, or review student class placement</p>
                    <div class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-amber-700 group-hover:gap-3 transition-all">
                        View <span>→</span>
                    </div>
                </div>
            </a>
        </div>
    </section>
@endsection
