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
        <x-sweet-alert-messages />

        @php
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

                <div class="flex items-center gap-8">

                    <div class="hidden items-center gap-3 md:flex">
                        <div class="text-right">
                            <p class="text-sm font-semibold text-slate-900">
                                {{ $teacher->name }} 
                                <span class="font-medium text-slate-500">- Teacher</span></p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" data-swal-logout>
                        @csrf
                        <button type="submit" class="inline-flex items-center text-sm font-semibold text-rose-600 underline decoration-transparent underline-offset-4 transition duration-200 hover:text-rose-700 hover:decoration-rose-300 focus-visible:outline-none focus-visible:decoration-rose-400">
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            <main class="mx-auto flex w-full max-w-7xl flex-col gap-8 px-6 pb-16 pt-2 lg:px-8 lg:pb-24">
                <section class="space-y-6">
                    <article class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/70 lg:p-10">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h2 class="text-3xl font-semibold tracking-tight text-slate-950">Learning workspace for courses, progress, and deadlines.</h2>
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

                </section>

                <div class="flex items-center gap-4">
                    <div class="h-px flex-1 bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>
                    <span class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Assigned Courses</span>
                    <div class="h-px flex-1 bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>
                </div>

                <section class="space-y-6">
                    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                        @forelse ($classCourses as $course)
                            @php
                                $style = $courseCardStyles[array_rand($courseCardStyles)];
                                $shape = $shapeSizes[array_rand($shapeSizes)];
                            @endphp
                            <a href="{{ route('teacher.course.show', $course['id']) }}" class="relative block overflow-hidden rounded-[1.75rem] border border-slate-200 {{ $style['surface'] }} p-6 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-200/70">
                                <div class="absolute -right-6 -top-4 {{ $shape }} rounded-full {{ $style['shape'] }} opacity-60"></div>
                                <div class="absolute bottom-[-1.25rem] left-[-1rem] h-16 w-16 rounded-[1.5rem] {{ $style['shape'] }} opacity-30 rotate-12"></div>

                                <div class="relative">
                                    <h3 class="text-xl font-semibold text-slate-950">{{ $course['name'] }}</h3>
                                    <p class="mt-2 text-sm text-slate-600">Class: {{ $course['class_name'] }}</p>
                                    <p class="mt-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">{{ $course['class_code'] }}</p>

                                    <div class="mt-6 space-y-4">
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="font-medium text-slate-600">Enrolled students</span>
                                            <span class="font-semibold text-slate-950">{{ $course['student_enrolled'] }}</span>
                                        </div>
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="font-medium text-slate-600">Not submitted</span>
                                            <span class="font-semibold text-slate-950">{{ $course['pending_submissions'] }}</span>
                                        </div>
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="font-medium text-slate-600">Submitted</span>
                                            <span class="font-semibold text-slate-950">{{ $course['submitted_submissions'] }}</span>
                                        </div>
                                    </div>

                                </div>
                            </a>
                        @empty
                            <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 text-sm text-slate-500 shadow-sm md:col-span-2 xl:col-span-3">
                                No assigned courses yet.
                            </div>
                        @endforelse
                    </div>
                </section>
            </main>
        </div>
    </body>
</html>
