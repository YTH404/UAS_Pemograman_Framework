<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Campus LMS') }} | Student Portal</title>

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
        @php
            $featuredCourses = [
                [
                    'title' => 'Web Programming Fundamentals',
                    'level' => 'Beginner',
                    'teacher' => 'Dina Rahma',
                    'schedule' => 'Mon & Wed · 08:00',
                    'lessons' => 12,
                    'students' => 48,
                    'accent' => 'from-sky-500 to-cyan-400',
                ],
                [
                    'title' => 'Database Design and SQL',
                    'level' => 'Intermediate',
                    'teacher' => 'Arif Pratama',
                    'schedule' => 'Tue · 10:00',
                    'lessons' => 14,
                    'students' => 36,
                    'accent' => 'from-emerald-500 to-teal-400',
                ],
                [
                    'title' => 'UI for Student Applications',
                    'level' => 'Project-based',
                    'teacher' => 'Maya Lestari',
                    'schedule' => 'Thu · 13:00',
                    'lessons' => 10,
                    'students' => 29,
                    'accent' => 'from-amber-500 to-orange-400',
                ],
            ];

            $announcements = [
                [
                    'tag' => 'Academic',
                    'title' => 'Midterm timetable is now available',
                    'body' => 'Review your room assignments, exam duration, and permitted materials before the week starts.',
                    'time' => 'Today · 08:00',
                ],
                [
                    'tag' => 'Platform',
                    'title' => 'New assignment submission flow is live',
                    'body' => 'Students can now upload files directly from each course card and track submission status in one place.',
                    'time' => 'Yesterday · 16:40',
                ],
                [
                    'tag' => 'Student Life',
                    'title' => 'Weekend mentoring sessions added',
                    'body' => 'Join the optional study clinics for help with JavaScript, database normalization, and final projects.',
                    'time' => 'May 10 · 11:15',
                ],
            ];

            $supportChannels = [
                ['label' => 'Email support', 'value' => 'support@campuslms.ac.id', 'note' => 'Response within 1 business day'],
                ['label' => 'Help desk', 'value' => '+62 812 3456 7890', 'note' => 'Mon - Fri, 08:00 - 17:00'],
                ['label' => 'Campus office', 'value' => 'Building B, 2nd Floor', 'note' => 'Walk-ins are welcome'],
            ];
        @endphp

        <div class="relative overflow-hidden">
            <div class="pointer-events-none absolute inset-0 -z-10 bg-[radial-gradient(circle_at_top_left,_rgba(14,165,233,0.16),_transparent_36%),radial-gradient(circle_at_top_right,_rgba(245,158,11,0.14),_transparent_28%),linear-gradient(to_bottom,_#f8fafc,_#eef2ff_48%,_#f8fafc)]"></div>

            <header class="mx-auto flex w-full max-w-7xl items-center justify-between px-6 py-5 lg:px-8">
                <a href="/" class="group inline-flex items-center gap-3">
                    <span class="grid h-11 w-11 place-items-center rounded-2xl bg-slate-900 text-sm font-semibold text-white shadow-lg shadow-slate-900/20 transition-transform duration-200 group-hover:-translate-y-0.5">LMS</span>
                    <span>
                        <span class="block text-[0.7rem] font-semibold uppercase tracking-[0.28em] text-sky-700">Campus learning</span>
                        <span class="block text-lg font-semibold text-slate-900">Student Portal</span>
                    </span>
                </a>

                <nav class="hidden items-center gap-8 text-sm font-medium text-slate-600 md:flex">
                    <a href="#courses" class="transition hover:text-slate-900">Courses</a>
                    <a href="#announcements" class="transition hover:text-slate-900">Announcements</a>
                    <a href="#support" class="transition hover:text-slate-900">Contact support</a>
                </nav>

                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-slate-900/20 transition hover:-translate-y-0.5 hover:bg-slate-800">
                    Login
                </a>
            </header>

            <main class="mx-auto flex w-full max-w-7xl flex-col gap-16 px-6 pb-16 pt-4 lg:px-8 lg:pb-24">
                <section class="grid items-center gap-10 lg:grid-cols-[1.2fr_0.8fr] lg:gap-14">
                    <div class="space-y-8">
                        <div class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white/80 px-4 py-2 text-sm font-medium text-slate-700 shadow-sm backdrop-blur">
                            <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                            Student dashboard, course updates, and support in one place
                        </div>

                        <div class="max-w-3xl space-y-5">
                            <h1 class="text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl lg:text-6xl">
                                A clean LMS home for students who want to start fast and stay organized.
                            </h1>
                            <p class="max-w-2xl text-lg leading-8 text-slate-600">
                                Access your classes, track deadlines, read announcements, and reach support without digging through clutter. Everything on this page is designed to help students get back to learning in seconds.
                            </p>
                        </div>

                        <div class="flex flex-col gap-4 sm:flex-row">
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-full bg-sky-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-sky-600/20 transition hover:-translate-y-0.5 hover:bg-sky-500">
                                Open student login
                            </a>
                            <a href="#courses" class="inline-flex items-center justify-center rounded-full border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:text-slate-950">
                                Browse featured courses
                            </a>
                        </div>

                        <dl class="grid gap-4 sm:grid-cols-3">
                            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                                <dt class="text-sm text-slate-500">Active courses</dt>
                                <dd class="mt-2 text-3xl font-semibold text-slate-950">18</dd>
                            </div>
                            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                                <dt class="text-sm text-slate-500">Assignments this week</dt>
                                <dd class="mt-2 text-3xl font-semibold text-slate-950">7</dd>
                            </div>
                            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                                <dt class="text-sm text-slate-500">Support reply time</dt>
                                <dd class="mt-2 text-3xl font-semibold text-slate-950">24h</dd>
                            </div>
                        </dl>
                    </div>

                    <aside class="relative">
                        <div class="absolute -left-6 top-10 h-24 w-24 rounded-full bg-sky-400/20 blur-3xl"></div>
                        <div class="absolute -right-8 bottom-12 h-28 w-28 rounded-full bg-amber-400/20 blur-3xl"></div>

                        <div class="relative overflow-hidden rounded-[2rem] border border-slate-200 bg-slate-950 text-white shadow-2xl shadow-slate-900/20">
                            <div class="border-b border-white/10 px-6 pb-5 pt-6">
                                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-sky-300">Student login</p>
                                <h2 class="mt-3 text-2xl font-semibold">Sign in to continue your learning journey</h2>
                                <p class="mt-3 text-sm leading-6 text-slate-300">
                                    Use your campus account to open course materials, check submissions, and review feedback.
                                </p>
                            </div>

                            <div class="space-y-5 px-6 py-6">
                                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="font-medium text-slate-200">Today’s focus</span>
                                        <span class="rounded-full bg-emerald-400/15 px-3 py-1 text-xs font-semibold text-emerald-300">Ready</span>
                                    </div>
                                    <ul class="mt-4 space-y-3 text-sm text-slate-300">
                                        <li class="flex items-start gap-3">
                                            <span class="mt-2 h-2 w-2 rounded-full bg-sky-400"></span>
                                            Review lecture notes for the web programming class.
                                        </li>
                                        <li class="flex items-start gap-3">
                                            <span class="mt-2 h-2 w-2 rounded-full bg-amber-300"></span>
                                            Submit the database design draft before 16:00.
                                        </li>
                                        <li class="flex items-start gap-3">
                                            <span class="mt-2 h-2 w-2 rounded-full bg-emerald-400"></span>
                                            Read the latest announcement from the academic office.
                                        </li>
                                    </ul>
                                </div>

                                <a href="{{ route('login') }}" class="inline-flex w-full items-center justify-center rounded-full bg-white px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-slate-100">
                                    Login now
                                </a>

                                <p class="text-center text-xs leading-5 text-slate-400">
                                    If you have trouble signing in, contact support below and include your student ID.
                                </p>
                            </div>
                        </div>
                    </aside>
                </section>

                <section id="courses" class="space-y-6">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-sky-700">Featured courses</p>
                            <h2 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950">A focused set of courses students can jump into immediately</h2>
                        </div>
                        <p class="max-w-xl text-sm leading-6 text-slate-600">
                            The featured list highlights active classes, instructor names, lesson counts, and meeting schedules to make the portal feel instantly useful.
                        </p>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                        @foreach ($featuredCourses as $course)
                            <article class="group rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-200/70">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-slate-600">{{ $course['level'] }}</span>
                                        <h3 class="mt-4 text-xl font-semibold text-slate-950">{{ $course['title'] }}</h3>
                                    </div>
                                    <div class="h-12 w-12 rounded-2xl bg-gradient-to-br {{ $course['accent'] }} shadow-lg shadow-slate-900/10"></div>
                                </div>

                                <dl class="mt-6 space-y-3 text-sm text-slate-600">
                                    <div class="flex items-center justify-between gap-4 border-b border-slate-100 pb-3">
                                        <dt>Instructor</dt>
                                        <dd class="font-medium text-slate-900">{{ $course['teacher'] }}</dd>
                                    </div>
                                    <div class="flex items-center justify-between gap-4 border-b border-slate-100 pb-3">
                                        <dt>Schedule</dt>
                                        <dd class="font-medium text-slate-900">{{ $course['schedule'] }}</dd>
                                    </div>
                                    <div class="flex items-center justify-between gap-4">
                                        <dt>Lessons / students</dt>
                                        <dd class="font-medium text-slate-900">{{ $course['lessons'] }} lessons · {{ $course['students'] }} students</dd>
                                    </div>
                                </dl>

                                <div class="mt-6 flex items-center justify-between text-sm text-slate-500">
                                    <span>Open course</span>
                                    <span class="transition group-hover:translate-x-1">→</span>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>

                <section id="announcements" class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
                    <div class="rounded-[2rem] bg-slate-900 px-7 py-8 text-white shadow-2xl shadow-slate-900/20">
                        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-sky-300">Announcements</p>
                        <h2 class="mt-3 text-3xl font-semibold tracking-tight">Important updates stay visible at the top of the student portal.</h2>
                        <p class="mt-4 max-w-xl text-sm leading-6 text-slate-300">
                            Use the announcements area for academic schedules, platform notices, and student-life reminders so everyone sees the same information quickly.
                        </p>

                        <div class="mt-8 grid gap-4 sm:grid-cols-2">
                            <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                                <p class="text-sm text-slate-400">Next class change</p>
                                <p class="mt-2 text-2xl font-semibold">Thursday 1 PM</p>
                            </div>
                            <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                                <p class="text-sm text-slate-400">New resources added</p>
                                <p class="mt-2 text-2xl font-semibold">12 files</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @foreach ($announcements as $announcement)
                            <article class="rounded-[1.5rem] border border-slate-200 bg-white p-6 shadow-sm">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                    <div>
                                        <span class="inline-flex rounded-full bg-sky-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-sky-700">{{ $announcement['tag'] }}</span>
                                        <h3 class="mt-4 text-lg font-semibold text-slate-950">{{ $announcement['title'] }}</h3>
                                        <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600">{{ $announcement['body'] }}</p>
                                    </div>
                                    <p class="text-sm font-medium text-slate-500 sm:pt-1">{{ $announcement['time'] }}</p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>

                <section id="support" class="grid gap-6 rounded-[2rem] border border-slate-200 bg-white p-7 shadow-sm lg:grid-cols-[1fr_0.9fr] lg:p-10">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-sky-700">Contact support</p>
                        <h2 class="mt-3 text-3xl font-semibold tracking-tight text-slate-950">Need help logging in or finding a course?</h2>
                        <p class="mt-4 max-w-2xl text-sm leading-6 text-slate-600">
                            The support section gives students direct contact options for account problems, course access issues, and general portal questions.
                        </p>

                        <div class="mt-8 grid gap-4 sm:grid-cols-3">
                            @foreach ($supportChannels as $channel)
                                <div class="rounded-3xl bg-slate-50 p-5 ring-1 ring-slate-200">
                                    <p class="text-sm font-medium text-slate-500">{{ $channel['label'] }}</p>
                                    <p class="mt-3 text-base font-semibold text-slate-950">{{ $channel['value'] }}</p>
                                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ $channel['note'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-[1.75rem] bg-slate-950 p-6 text-white shadow-2xl shadow-slate-900/20">
                        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-300">Fastest route</p>
                        <h3 class="mt-3 text-2xl font-semibold">Send a concise support message</h3>
                        <p class="mt-3 text-sm leading-6 text-slate-300">
                            Include your student ID, course name, and a short screenshot description so the support team can respond faster.
                        </p>

                        <div class="mt-6 space-y-3 rounded-3xl border border-white/10 bg-white/5 p-5 text-sm text-slate-300">
                            <p>• Login problem: account locked or password reset</p>
                            <p>• Course problem: missing materials or broken links</p>
                            <p>• Assignment problem: upload failures or deadline questions</p>
                        </div>

                        <a href="mailto:support@campuslms.ac.id" class="mt-6 inline-flex w-full items-center justify-center rounded-full bg-white px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-slate-100">
                            Email support team
                        </a>
                    </div>
                </section>
            </main>
        </div>
    </body>
</html>
