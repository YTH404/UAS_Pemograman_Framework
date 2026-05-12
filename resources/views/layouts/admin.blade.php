<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Campus LMS') }} | @yield('title', 'Admin Panel')</title>

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
        @php
            $navItems = [
                ['label' => 'Dashboard', 'route' => route('admin.dashboard'), 'active' => request()->routeIs('admin.dashboard')],
                ['label' => 'Manage Teacher', 'route' => route('admin.manage-teacher'), 'active' => request()->routeIs('admin.manage-teacher')],
                ['label' => 'Manage Class', 'route' => route('admin.manage-class'), 'active' => request()->routeIs('admin.manage-class')],
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
                            @yield('header-title')
                        </div>
                    </div>
                </header>

                <div class="flex flex-col gap-8 px-6 pb-16 pt-6 lg:px-8 lg:pb-24">
                    @yield('content')
                </div>
            </main>
        </div>

        <script>
            const toggle = document.getElementById('sidebar-toggle');
            const overlay = document.getElementById('sidebar-overlay');
            const sidebar = document.querySelector('aside');

            if (toggle) {
                toggle.addEventListener('change', function() {
                    sidebar.classList.toggle('translate-x-0', this.checked);
                    sidebar.classList.toggle('-translate-x-full', !this.checked);
                    overlay.classList.toggle('hidden', !this.checked);
                });

                overlay.addEventListener('click', function() {
                    toggle.checked = false;
                    sidebar.classList.add('-translate-x-full');
                    sidebar.classList.remove('translate-x-0');
                    overlay.classList.add('hidden');
                });
            }
        </script>
    </body>
</html>
