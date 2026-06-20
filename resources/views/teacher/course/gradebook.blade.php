<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Campus LMS') }} | Gradebook</title>

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
        <x-sweet-alert-messages />

        @php
            $courseCode = $course->classes?->class_code ?? 'COURSE';
            $courseTitle = $courseCode . '-' . $course->course_name;
        @endphp

        <div class="relative overflow-hidden">
            <div class="pointer-events-none absolute inset-0 -z-10 bg-[radial-gradient(circle_at_top_left,_rgba(14,165,233,0.16),_transparent_36%),radial-gradient(circle_at_top_right,_rgba(245,158,11,0.14),_transparent_28%),linear-gradient(to_bottom,_#f8fafc,_#eef2ff_48%,_#f8fafc)]"></div>

            <header class="mx-auto flex w-full max-w-7xl items-center justify-between px-6 py-5 lg:px-8">
                <a href="{{ route('teacher.course.show', $course->id) }}" class="group inline-flex items-center gap-3">
                    <span class="grid h-11 w-11 place-items-center rounded-2xl bg-slate-900 text-sm font-semibold text-white shadow-lg shadow-slate-900/20 transition-transform duration-200 group-hover:-translate-y-0.5">LMS</span>
                    <span>
                        <span class="block text-lg font-semibold text-slate-900">Gradebook</span>
                        <span class="block text-xs font-medium uppercase tracking-[0.2em] text-slate-500">Back to course</span>
                    </span>
                </a>

                <form method="POST" action="{{ route('logout') }}" data-swal-logout>
                    @csrf
                    <button type="submit" class="inline-flex items-center text-sm font-semibold text-rose-600 underline decoration-transparent underline-offset-4 transition duration-200 hover:text-rose-700 hover:decoration-rose-300 focus-visible:outline-none focus-visible:decoration-rose-400">
                        Logout
                    </button>
                </form>
            </header>

            <main class="mx-auto flex w-full max-w-7xl flex-col gap-8 px-6 pb-16 pt-2 lg:px-8 lg:pb-24">
                <section class="rounded-[2rem] border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/70 lg:p-10">
                    <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-sky-700">{{ $courseCode }}</p>
                            <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950 lg:text-4xl">Gradebook</h1>
                            <p class="mt-3 text-sm leading-6 text-slate-600">{{ $courseTitle }}</p>

                            <nav class="mt-4 flex flex-wrap items-center gap-2 text-sm text-slate-500">
                                <a href="{{ route('teacher.dashboard') }}" class="font-medium text-slate-700 transition hover:text-slate-950">Dashboard</a>
                                <span>/</span>
                                <a href="{{ route('teacher.course.show', $course->id) }}" class="font-medium text-slate-700 transition hover:text-slate-950">My courses</a>
                                <span>/</span>
                                <span class="font-semibold text-sky-700">Gradebook</span>
                            </nav>
                        </div>

                        <a href="{{ route('teacher.course.show', $course->id) }}" class="inline-flex w-fit items-center rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-100">
                            Back to Course
                        </a>
                    </div>

                    {{-- <div class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                        <div class="rounded-3xl bg-slate-950 p-5 text-white shadow-sm ring-1 ring-white/10">
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-sky-300">Students</p>
                            <p class="mt-4 text-4xl font-semibold">{{ $gradebook['rows']->count() }}</p>
                            <p class="mt-3 text-sm leading-6 text-slate-300">Current students in this class.</p>
                        </div>
                        <div class="rounded-3xl bg-slate-950 p-5 text-white shadow-sm ring-1 ring-white/10">
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-sky-300">Weights</p>
                            <p class="mt-4 text-4xl font-semibold">{{ $gradebook['is_configured'] ? 'Locked' : 'Open' }}</p>
                            <p class="mt-3 text-sm leading-6 text-slate-300">Course grade percentages.</p>
                        </div>
                        <div class="rounded-3xl bg-slate-950 p-5 text-white shadow-sm ring-1 ring-white/10">
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-sky-300">UAS</p>
                            <p class="mt-4 text-4xl font-semibold">{{ $gradebook['has_uas'] ? 'Ready' : 'Pending' }}</p>
                            <p class="mt-3 text-sm leading-6 text-slate-300">Final grades unlock after UAS exists.</p>
                        </div>
                        <div class="rounded-3xl bg-slate-950 p-5 text-white shadow-sm ring-1 ring-white/10">
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-sky-300">Final Grades</p>
                            <p class="mt-4 text-4xl font-semibold">{{ $gradebook['is_available'] ? 'Shown' : 'Hidden' }}</p>
                            <p class="mt-3 text-sm leading-6 text-slate-300">Teacher-only grade visibility.</p>
                        </div>
                    </div> --}}
                </section>

                @if (! $gradebook['is_configured'])
                    <section class="rounded-[1.75rem] border border-amber-200 bg-amber-50 p-6 text-sm leading-6 text-amber-900 shadow-sm">
                        Lock grade weights from the course page before using this gradebook.
                    </section>
                @elseif (! $gradebook['is_available'])
                    <section class="rounded-[1.75rem] border border-amber-200 bg-amber-50 p-6 text-sm leading-6 text-amber-900 shadow-sm">
                        Final numeric scores and letter grades will appear after a UAS assignment is created in Pertemuan 16.
                    </section>
                @endif

                <section class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-6 py-5">
                        <h2 class="text-sm font-semibold uppercase tracking-[0.2em] text-sky-700">Final Gradebook</h2>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                            <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                                <tr>
                                    <th scope="col" class="px-6 py-4">Student</th>
                                    <th scope="col" class="px-6 py-4">Final Score</th>
                                    <th scope="col" class="px-6 py-4">Letter</th>
                                    @foreach ($gradebook['labels'] as $key => $label)
                                        <th scope="col" class="px-6 py-4">{{ $label }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white">
                                @forelse ($gradebook['rows'] as $row)
                                    <tr>
                                        <td class="min-w-[16rem] px-6 py-4 align-top">
                                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">{{ $row['student']->username }}</p>
                                            <p class="mt-1 font-semibold text-slate-950">{{ $row['student']->name }}</p>
                                        </td>
                                        @if ($gradebook['is_available'])
                                            <td class="whitespace-nowrap px-6 py-4 align-top font-semibold text-slate-950">{{ $row['final_score'] }}</td>
                                            <td class="whitespace-nowrap px-6 py-4 align-top">
                                                <span class="inline-flex rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700">{{ $row['letter_grade'] }}</span>
                                            </td>
                                            @foreach ($gradebook['labels'] as $key => $label)
                                                @php
                                                    $component = $row['components'][$key];
                                                @endphp
                                                <td class="min-w-[10rem] px-6 py-4 align-top text-slate-600">
                                                    <p class="font-semibold text-slate-900">{{ $component['average'] }}%</p>
                                                    <p class="mt-1 text-xs text-slate-500">{{ $component['contribution'] }} of {{ $component['weight'] }}%</p>
                                                </td>
                                            @endforeach
                                        @else
                                            <td class="whitespace-nowrap px-6 py-4 align-top text-slate-500">Pending UAS</td>
                                            <td class="whitespace-nowrap px-6 py-4 align-top text-slate-500">-</td>
                                            @foreach ($gradebook['labels'] as $key => $label)
                                                <td class="whitespace-nowrap px-6 py-4 align-top text-slate-500">{{ $gradebook['weights'][$key] }}%</td>
                                            @endforeach
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-6 text-sm text-slate-500">No students are currently enrolled in this class.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </main>
        </div>
    </body>
</html>
