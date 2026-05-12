<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Campus LMS') }} | Manage Teachers</title>

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
        @php
            $navItems = [
                ['label' => 'Dashboard', 'route' => route('admin.dashboard'), 'active' => false],
                ['label' => 'Manage Teacher', 'route' => route('admin.manage-teacher'), 'active' => true],
                ['label' => 'Manage Class', 'route' => route('admin.manage-class'), 'active' => false],
            ];

            $teachers = [
                ['id' => 1, 'name' => 'Dr. Ahmad Wijaya', 'email' => 'ahmad@lms.local', 'specialization' => 'Web Development', 'courses' => 4, 'status' => 'Active'],
                ['id' => 2, 'name' => 'Dina Rahma', 'email' => 'dina@lms.local', 'specialization' => 'Database Design', 'courses' => 3, 'status' => 'Active'],
                ['id' => 3, 'name' => 'Budi Santoso', 'email' => 'budi@lms.local', 'specialization' => 'UI/UX Design', 'courses' => 2, 'status' => 'Active'],
                ['id' => 4, 'name' => 'Siti Nurhaliza', 'email' => 'siti@lms.local', 'specialization' => 'Software Engineering', 'courses' => 5, 'status' => 'Inactive'],
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
                            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-sky-700">Teacher Management</p>
                        </div>
                    </div>
                </header>

                <div class="flex flex-col gap-8 px-6 pb-16 pt-6 lg:px-8 lg:pb-24">
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
