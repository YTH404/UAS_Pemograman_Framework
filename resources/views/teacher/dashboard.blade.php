<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Campus LMS') }} | Teacher Dashboard</title>

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
        @php
            $classOptions = ['Class A', 'Class B', 'Class C', 'Class D'];

            $teacherTotals = [
                ['label' => 'Total class assigned', 'value' => 4, 'note' => 'Active groups this term'],
                ['label' => 'Total course assigned', 'value' => 7, 'note' => 'Courses under your supervision'],
                ['label' => 'Total course in this class', 'value' => 3, 'note' => 'Courses opened in the selected class'],
            ];

            $classCourses = [
                [
                    'name' => 'Web Programming Fundamentals',
                    'student_enrolled' => 28,
                    'pending_submissions' => 5,
                    'submitted_submissions' => 23,
                ],
                [
                    'name' => 'Database Design and SQL',
                    'student_enrolled' => 31,
                    'pending_submissions' => 8,
                    'submitted_submissions' => 23,
                ],
                [
                    'name' => 'UI for Student Applications',
                    'student_enrolled' => 25,
                    'pending_submissions' => 2,
                    'submitted_submissions' => 23,
                ],
                [
                    'name' => 'Software Engineering Basics',
                    'student_enrolled' => 29,
                    'pending_submissions' => 11,
                    'submitted_submissions' => 18,
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
                        <span class="block text-lg font-semibold text-slate-900">Teacher Dashboard</span>
                    </span>
                </a>

                <div class="hidden items-center gap-3 md:flex">
                    <div class="text-right">
                        <p class="text-sm font-semibold text-slate-900">Dina Rahma</p>
                        <p class="text-xs text-slate-500">Course coordinator</p>
                    </div>
                    <div class="grid h-11 w-11 place-items-center rounded-full bg-sky-100 text-sm font-semibold text-sky-700 ring-1 ring-sky-200">DR</div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full rounded-2xl bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700 shadow-sm transition duration-200 hover:bg-rose-100 hover:shadow-md hover:shadow-rose-200/70">
                        Logout
                    </button>
                </form>
            </header>

            <main class="mx-auto flex w-full max-w-7xl flex-col gap-8 px-6 pb-16 pt-2 lg:px-8 lg:pb-24">
                <section class="space-y-6">
                    <article class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/70 lg:p-10">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h2 class="text-3xl font-semibold tracking-tight text-slate-950">Your learning workspace for courses, progress, and deadlines.</h2>
                            </div>
                        </div>

                        <div class="mt-8 grid gap-4 lg:grid-cols-3">
                            @foreach ($teacherTotals as $total)
                                <div class="rounded-3xl bg-slate-950 p-5 text-white shadow-sm ring-1 ring-white/10">
                                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-sky-300">{{ $total['label'] }}</p>
                                    <p class="mt-4 text-4xl font-semibold">{{ $total['value'] }}</p>
                                    <p class="mt-3 text-sm leading-6 text-slate-300">{{ $total['note'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </article>

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                        {{-- <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-sky-700">Assigned classes</p>
                            <h2 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950">Select the class you want to review</h2>
                        </div> --}}

                        <label class="inline-flex min-w-64 flex-col gap-2 rounded-3xl border border-slate-200 bg-white px-5 py-4 shadow-sm">
                            <span class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Choose class</span>
                            <select class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white">
                                @foreach ($classOptions as $classOption)
                                    <option>{{ $classOption }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>
                </section>

                <div class="flex items-center gap-4">
                    <div class="h-px flex-1 bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>
                    <span class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Assigned Courses</span>
                    <div class="h-px flex-1 bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>
                </div>

                <section class="space-y-6">
                    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                        @foreach ($classCourses as $course)
                            @php
                                $style = $courseCardStyles[array_rand($courseCardStyles)];
                                $shape = $shapeSizes[array_rand($shapeSizes)];
                            @endphp
                            <article class="relative overflow-hidden rounded-[1.75rem] border border-slate-200 {{ $style['surface'] }} p-6 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-200/70">
                                <div class="absolute -right-6 -top-4 {{ $shape }} rounded-full {{ $style['shape'] }} opacity-60"></div>
                                <div class="absolute bottom-[-1.25rem] left-[-1rem] h-16 w-16 rounded-[1.5rem] {{ $style['shape'] }} opacity-30 rotate-12"></div>

                                <div class="relative">
                                    <h3 class="text-xl font-semibold text-slate-950">{{ $course['name'] }}</h3>

                                    <div class="mt-6 space-y-4">
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="font-medium text-slate-600">Student enrolled</span>
                                            <span class="font-semibold text-slate-950">{{ $course['student_enrolled'] }}</span>
                                        </div>
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="font-medium text-slate-600">Pending submission</span>
                                            <span class="font-semibold text-slate-950">{{ $course['pending_submissions'] }}</span>
                                        </div>
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="font-medium text-slate-600">Submitted submission</span>
                                            <span class="font-semibold text-slate-950">{{ $course['submitted_submissions'] }}</span>
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