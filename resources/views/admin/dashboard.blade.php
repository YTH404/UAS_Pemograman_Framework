<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Campus LMS') }} | Admin Dashboard</title>

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
        @php
            $navItems = [
                ['label' => 'Dashboard', 'route' => route('admin.dashboard'), 'active' => true],
                ['label' => 'Manage Teacher', 'route' => route('admin.manage-teacher'), 'active' => false],
                ['label' => 'Manage Class', 'route' => route('admin.manage-class'), 'active' => false],
            ];

            $dashboardStats = [
                ['label' => 'Total Teachers', 'value' => 24, 'note' => 'Active instructors'],
                ['label' => 'Total Classes', 'value' => 12, 'note' => 'Active learning groups'],
                ['label' => 'Total Students', 'value' => 456, 'note' => 'Enrolled students'],
            ];
        @endphp

        <div class="flex min-h-screen">
            <!-- Sidebar -->
            <aside class="fixed inset-y-0 left-0 z-40 w-64 border-r border-slate-200 bg-white shadow-lg lg:relative">
                <div class="flex h-full flex-col overflow-y-auto">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-6 py-3">
                        <a href="{{ url('/') }}" class="group inline-flex items-center gap-3">
                            <span>
                                <span class="block text-lg font-semibold text-slate-900">Admin Panel</span>
                            </span>
                        </a>
                    </div>

                    <nav class="flex-1 space-y-2 px-4 py-6">
                        @foreach ($navItems as $item)
                            <a href="{{ $item['route'] }}" class="block rounded-2xl px-4 py-3 text-sm font-semibold transition-all duration-200 {{ $item['active'] ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/20' : 'text-slate-600 hover:bg-slate-100' }}">
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </nav>

                    <div class="border-t border-slate-200 px-4 py-4">
                        <div class="flex items-center gap-3 rounded-2xl bg-slate-50 p-4">
                            <div class="grid h-10 w-10 place-items-center rounded-full bg-purple-100 text-xs font-semibold text-purple-700 ring-1 ring-purple-200">AU</div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-slate-900">Admin User</p>
                                <p class="text-xs text-slate-500">System administrator</p>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Mobile Sidebar Toggle -->
            <input type="checkbox" id="sidebar-toggle" class="hidden" />
            <div class="fixed inset-0 z-30 hidden bg-black/50 lg:hidden" id="sidebar-overlay"></div>

            <!-- Main Content -->
            <div class="pointer-events-none absolute inset-0 -z-10 bg-[radial-gradient(circle_at_top_left,_rgba(14,165,233,0.16),_transparent_36%),radial-gradient(circle_at_top_right,_rgba(245,158,11,0.14),_transparent_28%),linear-gradient(to_bottom,_#f8fafc,_#eef2ff_48%,_#f8fafc)]"></div>
            <main class="w-full flex-1 overflow-y-auto">
                <header class="flex items-center justify-between border-b border-slate-200 bg-white/50 backdrop-blur px-6 py-4 lg:px-8">
                    <label for="sidebar-toggle" class="inline-flex items-center justify-center rounded-lg p-2 text-slate-600 hover:bg-slate-100 lg:hidden">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </label>
                    <div class="flex items-center gap-3">
                        <div class="text-right">
                            {{-- <p class="text-sm font-semibold text-slate-900">Admin User</p>
                            <p class="text-xs text-slate-500">System administrator</p> --}}
                            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-sky-700">Dashboard</p>
                        </div>
                        {{-- <div class="grid h-11 w-11 place-items-center rounded-full bg-purple-100 text-sm font-semibold text-purple-700 ring-1 ring-purple-200">AU</div> --}}
                    </div>
                </header>

                <div class="flex flex-col gap-8 px-6 pb-16 pt-6 lg:px-8 lg:pb-24">
                <section class="space-y-6">
                    <article class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/70 lg:p-10">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h2 class="text-3xl font-semibold tracking-tight text-slate-950">System Overview</h2>
                                <p class="mt-2 text-slate-600">Monitor key metrics and system activity</p>
                            </div>
                        </div>

                        <div class="mt-8 grid gap-4 lg:grid-cols-3">
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
                        <a href="{{ route('admin.manage-teacher') }}" class="group relative overflow-hidden rounded-[1.75rem] border border-slate-200 bg-sky-50 p-6 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-200/70">
                            <div class="absolute -right-6 -top-4 h-24 w-24 rounded-full bg-sky-200/70 opacity-60"></div>
                            <div class="relative">
                                <h3 class="text-xl font-semibold text-slate-950">Manage Teachers</h3>
                                <p class="mt-2 text-sm text-slate-600">Add, edit, or remove instructor accounts</p>
                                <div class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-sky-700 group-hover:gap-3 transition-all">
                                    View <span>→</span>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('admin.manage-class') }}" class="group relative overflow-hidden rounded-[1.75rem] border border-slate-200 bg-emerald-50 p-6 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-200/70">
                            <div class="absolute -right-6 -top-4 h-24 w-24 rounded-full bg-emerald-200/70 opacity-60"></div>
                            <div class="relative">
                                <h3 class="text-xl font-semibold text-slate-950">Manage Classes</h3>
                                <p class="mt-2 text-sm text-slate-600">Create, edit, or organize learning groups</p>
                                <div class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 group-hover:gap-3 transition-all">
                                    View <span>→</span>
                                </div>
                            </div>
                        </a>

                        <div class="relative overflow-hidden rounded-[1.75rem] border border-slate-200 bg-amber-50 p-6 shadow-sm">
                            <div class="absolute -right-6 -top-4 h-24 w-24 rounded-full bg-amber-200/70 opacity-60"></div>
                            <div class="relative">
                                <h3 class="text-xl font-semibold text-slate-950">System Settings</h3>
                                <p class="mt-2 text-sm text-slate-600">Configure application preferences and policies</p>
                                <div class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-amber-700">
                                    Coming soon <span>→</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </body>
</html>
