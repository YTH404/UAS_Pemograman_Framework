<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Campus LMS') }} | Student Dashboard</title>

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
        @php
            $courseSummary = [
                [
                    'name' => 'Web Programming Fundamentals',
                    'teacher' => 'Dina Rahma',
                    'progress' => 78,
                    'deadline' => 'Tomorrow · 16:00',
                ],
                [
                    'name' => 'Database Design and SQL',
                    'teacher' => 'Arif Pratama',
                    'progress' => 64,
                    'deadline' => 'Thu · 11:30',
                ],
                [
                    'name' => 'UI for Student Applications',
                    'teacher' => 'Maya Lestari',
                    'progress' => 91,
                    'deadline' => 'Fri · 09:00',
                ],
                [
                    'name' => 'Web Programming Fundamentals',
                    'teacher' => 'Dina Rahma',
                    'progress' => 78,
                    'deadline' => 'Tomorrow · 16:00',
                ],
                [
                    'name' => 'Database Design and SQL',
                    'teacher' => 'Arif Pratama',
                    'progress' => 64,
                    'deadline' => 'Thu · 11:30',
                ],
                [
                    'name' => 'UI for Student Applications',
                    'teacher' => 'Maya Lestari',
                    'progress' => 91,
                    'deadline' => 'Fri · 09:00',
                ],
            ];

            $upcomingAssignments = [
                [
                    'course' => 'Web Programming Fundamentals',
                    'task' => 'Sprint 3 assignment',
                    'due' => 'Tomorrow · 16:00',
                    'type' => 'Essay + code submission',
                ],
                [
                    'course' => 'Database Design and SQL',
                    'task' => 'ER diagram revision',
                    'due' => 'Thu · 11:30',
                    'type' => 'Diagram upload',
                ],
            ];

            $courseCardStyles = [
                ['surface' => 'bg-sky-50', 'shape' => 'bg-sky-200/70'],
                ['surface' => 'bg-emerald-50', 'shape' => 'bg-emerald-200/70'],
                ['surface' => 'bg-amber-50', 'shape' => 'bg-amber-200/70'],
                ['surface' => 'bg-rose-50', 'shape' => 'bg-rose-200/70'],
                ['surface' => 'bg-violet-50', 'shape' => 'bg-violet-200/70'],
                ['surface' => 'bg-cyan-50', 'shape' => 'bg-cyan-200/70'],
            ];

            $shapeSizes = ['h-20 w-20', 'h-24 w-24', 'h-28 w-28'];
        @endphp

        <div class="relative overflow-hidden">
            <div class="pointer-events-none absolute inset-0 -z-10 bg-[radial-gradient(circle_at_top_left,_rgba(14,165,233,0.16),_transparent_36%),radial-gradient(circle_at_top_right,_rgba(245,158,11,0.14),_transparent_28%),linear-gradient(to_bottom,_#f8fafc,_#eef2ff_48%,_#f8fafc)]"></div>

            <header class="mx-auto flex w-full max-w-7xl items-center justify-between px-6 py-5 lg:px-8">
                <a href="{{ url('/') }}" class="group inline-flex items-center gap-3">
                    <span class="grid h-11 w-11 place-items-center rounded-2xl bg-slate-900 text-sm font-semibold text-white shadow-lg shadow-slate-900/20 transition-transform duration-200 group-hover:-translate-y-0.5">LMS</span>
                    <span>
                        {{-- <span class="block text-[0.7rem] font-semibold uppercase tracking-[0.28em] text-sky-700">Student area</span> --}}
                        <span class="block text-lg font-semibold text-slate-900">Dashboard</span>
                    </span>
                </a>

                <div class="hidden items-center gap-3 md:flex">
                    <div class="text-right">
                        <p class="text-sm font-semibold text-slate-900">Alya Putri</p>
                        <p class="text-xs text-slate-500">Computer Science · Semester 4</p>
                    </div>
                    <div class="grid h-11 w-11 place-items-center rounded-full bg-sky-100 text-sm font-semibold text-sky-700 ring-1 ring-sky-200">AP</div>
                </div>
            </header>

            <main class="mx-auto flex w-full max-w-7xl flex-col gap-8 px-6 pb-16 pt-2 lg:px-8 lg:pb-24">
                <section class="grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
                    <article class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/70 lg:p-10">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    {{-- <p class="text-sm font-semibold uppercase tracking-[0.22em] text-sky-700">Your Course</p> --}}
                                    <h2 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950">Your learning workspace for courses, progress, and deadlines.</h2>
                                </div>
                                {{-- <div class="rounded-full bg-slate-950 px-4 py-2 text-sm font-semibold text-white">3 courses</div> --}}
                            </div>

                            <div class="mt-8 grid gap-4 lg:grid-cols-2">
                                <div class="rounded-3xl bg-slate-950 p-5 text-white shadow-sm ring-1 ring-white/10">
                                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-sky-300">Enrolled course total</p>
                                    <p class="mt-4 text-4xl font-semibold">{{ count($courseSummary) }}</p>
                                    <p class="mt-3 text-sm leading-6 text-slate-300">Active courses enrolled for this semester.</p>
                                </div>

                                <div class="rounded-3xl bg-slate-950 p-5 text-white shadow-sm ring-1 ring-white/10">
                                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-sky-300">Total upcoming assignment</p>
                                    <p class="mt-4 text-4xl font-semibold">{{ count(array_slice($upcomingAssignments, 0, 3)) }}</p>
                                    <p class="mt-3 text-sm leading-6 text-slate-300">Tasks scheduled next and kept visible in the assignment card.</p>
                                </div>
                            </div>
                        </article>

                    <article class="overflow-hidden rounded-[2rem] bg-slate-950 p-8 text-white shadow-2xl shadow-slate-900/20 lg:p-10">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-sky-300">Upcoming Assignments</p>
                                    <h2 class="mt-2 text-3xl font-semibold tracking-tight">Maximum of 3 tasks so the list stays focused</h2>
                                </div>
                                <div class="rounded-full bg-white/10 px-4 py-2 text-sm font-semibold text-slate-100">{{ count($upcomingAssignments) }} tasks</div>
                            </div>

                            <div class="mt-8 space-y-4">
                                @foreach (array_slice($upcomingAssignments, 0, 3) as $assignment)
                                    <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <p class="text-sm text-slate-300">{{ $assignment['course'] }}</p>
                                                <h3 class="mt-1 text-lg font-semibold text-white">{{ $assignment['task'] }}</h3>
                                            </div>
                                            <span class="rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-sky-200">{{ $assignment['due'] }}</span>
                                        </div>
                                        <p class="mt-3 text-sm leading-6 text-slate-300">{{ $assignment['type'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </article>
                </section>

                <div class="flex items-center gap-4">
                    <div class="h-px flex-1 bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>
                    <span class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Enrolled Courses</span>
                    <div class="h-px flex-1 bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>
                </div>

                <section class="space-y-6">
                    <div class="flex items-end justify-between gap-4">
                        {{-- <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-sky-700">Enrolled courses</p>
                            <h2 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950">Course cards with color, shape, title, teacher, and progress</h2>
                        </div>
                        <a href="{{ route('login') }}" class="hidden rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:text-slate-950 sm:inline-flex">
                            Back to portal
                        </a> --}}
                    </div>

                    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                        @foreach ($courseSummary as $course)
                            @php
                                $style = $courseCardStyles[array_rand($courseCardStyles)];
                                $shape = $shapeSizes[array_rand($shapeSizes)];
                            @endphp
                            <article class="relative overflow-hidden rounded-[1.75rem] border border-slate-200 {{ $style['surface'] }} p-6 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-200/70">
                                <div class="absolute -right-6 -top-4 {{ $shape }} rounded-full {{ $style['shape'] }} opacity-60"></div>
                                <div class="absolute bottom-[-1.25rem] left-[-1rem] h-16 w-16 rounded-[1.5rem] {{ $style['shape'] }} opacity-30 rotate-12"></div>

                                <div class="relative">
                                    <h3 class="text-xl font-semibold text-slate-950">{{ $course['name'] }}</h3>
                                    <p class="mt-2 text-sm text-slate-600">Teacher: {{ $course['teacher'] }}</p>

                                    <div class="mt-6 space-y-3">
                                        <div class="flex items-center justify-between text-sm font-medium text-slate-600">
                                            <span>Progress</span>
                                            <span>{{ $course['progress'] }}%</span>
                                        </div>
                                        <div class="h-3 rounded-full bg-white/80 ring-1 ring-black/5">
                                            <div class="h-3 rounded-full bg-gradient-to-r from-slate-900 to-slate-600" style="width: {{ $course['progress'] }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            </main>
        </div>
    </body>
</html>