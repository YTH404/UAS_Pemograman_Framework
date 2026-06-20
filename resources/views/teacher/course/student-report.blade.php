<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Campus LMS') }} | Student Activity Report</title>

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
        <x-sweet-alert-messages />

        @php
            $courseCode = $course->classes?->class_code ?? 'COURSE';
            $courseTitle = $courseCode . '-' . $course->course_name;
            $statusClasses = [
                'success' => 'bg-emerald-100 text-emerald-700',
                'danger' => 'bg-rose-100 text-rose-700',
                'warning' => 'bg-amber-100 text-amber-700',
                'muted' => 'bg-slate-100 text-slate-600',
            ];
            $formatDate = fn ($date) => $date?->format('d M Y H:i') ?? '-';
        @endphp

        <div class="relative overflow-hidden">
            <div class="pointer-events-none absolute inset-0 -z-10 bg-[radial-gradient(circle_at_top_left,_rgba(14,165,233,0.16),_transparent_36%),radial-gradient(circle_at_top_right,_rgba(245,158,11,0.14),_transparent_28%),linear-gradient(to_bottom,_#f8fafc,_#eef2ff_48%,_#f8fafc)]"></div>

            <header class="mx-auto flex w-full max-w-7xl items-center justify-between px-6 py-5 lg:px-8">
                <a href="{{ route('teacher.course.show', $course->id) }}" class="group inline-flex items-center gap-3">
                    <span class="grid h-11 w-11 place-items-center rounded-2xl bg-slate-900 text-sm font-semibold text-white shadow-lg shadow-slate-900/20 transition-transform duration-200 group-hover:-translate-y-0.5">LMS</span>
                    <span>
                        <span class="block text-lg font-semibold text-slate-900">Student Activity</span>
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
                            <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950 lg:text-4xl">Student Activity Report</h1>
                            <p class="mt-3 text-sm leading-6 text-slate-600">{{ $courseTitle }}</p>

                            <nav class="mt-4 flex flex-wrap items-center gap-2 text-sm text-slate-500">
                                <a href="{{ route('teacher.dashboard') }}" class="font-medium text-slate-700 transition hover:text-slate-950">Dashboard</a>
                                <span>/</span>
                                <a href="{{ route('teacher.course.show', $course->id) }}" class="font-medium text-slate-700 transition hover:text-slate-950">My courses</a>
                                <span>/</span>
                                <span class="font-semibold text-sky-700">Student Report</span>
                            </nav>
                        </div>

                        <a href="{{ route('teacher.course.show', $course->id) }}" class="inline-flex w-fit items-center rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-100">
                            Back to Course
                        </a>
                    </div>

                    <form method="GET" action="{{ route('teacher.course.students.report', $course->id) }}" class="mt-8 grid gap-4 rounded-3xl border border-slate-200 bg-slate-50 p-5 lg:grid-cols-[1fr_auto] lg:items-end">
                        <label class="block">
                            <span class="mb-2 block text-sm font-semibold text-slate-700">Choose Student</span>
                            <select name="student_id" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-500" @disabled($studentClasses->isEmpty())>
                                @forelse ($studentClasses as $studentClass)
                                    <option value="{{ $studentClass->student_id }}" @selected($selectedStudent?->id === $studentClass->student_id)>
                                        {{ $studentClass->student->name }} ({{ $studentClass->student->username }})
                                    </option>
                                @empty
                                    <option>No students available</option>
                                @endforelse
                            </select>
                        </label>

                        <button type="submit" class="inline-flex h-fit w-fit items-center justify-center rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/20 transition hover:-translate-y-0.5 hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-60" @disabled($studentClasses->isEmpty())>
                            View Student
                        </button>
                    </form>

                    <div class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                        <div class="rounded-3xl bg-slate-950 p-5 text-white shadow-sm ring-1 ring-white/10">
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-sky-300">Students</p>
                            <p class="mt-4 text-4xl font-semibold">{{ $summary['students'] }}</p>
                            <p class="mt-3 text-sm leading-6 text-slate-300">Current students in this class.</p>
                        </div>
                        <div class="rounded-3xl bg-slate-950 p-5 text-white shadow-sm ring-1 ring-white/10">
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-sky-300">Attendance</p>
                            <p class="mt-4 text-4xl font-semibold">{{ $summary['attendance_present'] }}/{{ $summary['attendance_total'] }}</p>
                            <p class="mt-3 text-sm leading-6 text-slate-300">Filled records for the selected student.</p>
                        </div>
                        <div class="rounded-3xl bg-slate-950 p-5 text-white shadow-sm ring-1 ring-white/10">
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-sky-300">Submitted</p>
                            <p class="mt-4 text-4xl font-semibold">{{ $summary['submitted'] }}/{{ $summary['submission_total'] }}</p>
                            <p class="mt-3 text-sm leading-6 text-slate-300">Assignments submitted by the selected student.</p>
                        </div>
                        <div class="rounded-3xl bg-slate-950 p-5 text-white shadow-sm ring-1 ring-white/10">
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-sky-300">Graded</p>
                            <p class="mt-4 text-4xl font-semibold">{{ $summary['graded'] }}/{{ $summary['submission_total'] }}</p>
                            <p class="mt-3 text-sm leading-6 text-slate-300">Selected student submissions with grades.</p>
                        </div>
                    </div>
                </section>

                <div class="flex items-center gap-4">
                    <div class="h-px flex-1 bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>
                    <span class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Selected Student</span>
                    <div class="h-px flex-1 bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>
                </div>

                @if ($studentReport)
                    @php
                        $student = $studentReport['student'];
                    @endphp

                    <section class="space-y-6">
                        <article class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ $student->username }}</p>
                            <h2 class="mt-2 text-2xl font-semibold text-slate-950">{{ $student->name }}</h2>
                        </article>

                        <section class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm">
                            <div class="border-b border-slate-200 px-6 py-5">
                                <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-700">Attendance</h3>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                                        <tr>
                                            <th scope="col" class="px-6 py-4">Meeting</th>
                                            <th scope="col" class="px-6 py-4">Opens</th>
                                            <th scope="col" class="px-6 py-4">Closes</th>
                                            <th scope="col" class="px-6 py-4">Filled</th>
                                            <th scope="col" class="px-6 py-4">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 bg-white">
                                        @forelse ($studentReport['attendances'] as $attendanceReport)
                                            @php
                                                $attendance = $attendanceReport['attendance'];
                                                $attendanceRecord = $attendanceReport['record'];
                                                $status = $attendanceReport['status'];
                                                $statusClass = $statusClasses[$status['variant']] ?? $statusClasses['muted'];
                                            @endphp

                                            <tr>
                                                <td class="min-w-[16rem] px-6 py-4 align-top">
                                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">{{ $attendance->meeting }}</p>
                                                    <p class="mt-1 font-semibold text-slate-950">{{ $attendance->title }}</p>
                                                </td>
                                                <td class="whitespace-nowrap px-6 py-4 align-top text-slate-600">{{ $formatDate($attendance->started_at) }}</td>
                                                <td class="whitespace-nowrap px-6 py-4 align-top text-slate-600">{{ $formatDate($attendance->ended_at) }}</td>
                                                <td class="whitespace-nowrap px-6 py-4 align-top text-slate-600">{{ $formatDate($attendanceRecord?->filled_at) }}</td>
                                                <td class="whitespace-nowrap px-6 py-4 align-top">
                                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                                        {{ $status['label'] }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-6 py-6 text-sm text-slate-500">No attendance has been created for this course.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </section>

                        <section class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm">
                            <div class="border-b border-slate-200 px-6 py-5">
                                <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-violet-700">Submissions</h3>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                                        <tr>
                                            <th scope="col" class="px-6 py-4">Assignment</th>
                                            <th scope="col" class="px-6 py-4">Opens</th>
                                            <th scope="col" class="px-6 py-4">Closes</th>
                                            <th scope="col" class="px-6 py-4">Submitted</th>
                                            <th scope="col" class="px-6 py-4">Grade</th>
                                            <th scope="col" class="px-6 py-4">Files</th>
                                            <th scope="col" class="px-6 py-4">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 bg-white">
                                        @forelse ($studentReport['submissions'] as $submissionReport)
                                            @php
                                                $assignment = $submissionReport['assignment'];
                                                $submission = $submissionReport['submission'];
                                                $status = $submissionReport['status'];
                                                $statusClass = $statusClasses[$status['variant']] ?? $statusClasses['muted'];
                                            @endphp

                                            <tr>
                                                <td class="min-w-[16rem] px-6 py-4 align-top">
                                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">{{ $assignment->meeting }}</p>
                                                    <p class="mt-1 font-semibold text-slate-950">{{ $assignment->title }}</p>
                                                </td>
                                                <td class="whitespace-nowrap px-6 py-4 align-top text-slate-600">{{ $formatDate($assignment->started_at) }}</td>
                                                <td class="whitespace-nowrap px-6 py-4 align-top text-slate-600">{{ $formatDate($assignment->ended_at) }}</td>
                                                <td class="whitespace-nowrap px-6 py-4 align-top text-slate-600">{{ $formatDate($submission?->submitted_at) }}</td>
                                                <td class="whitespace-nowrap px-6 py-4 align-top text-slate-600">{{ $submission?->grade !== null ? $submission->grade . '/100' : '-' }}</td>
                                                <td class="min-w-[12rem] px-6 py-4 align-top">
                                                    @if ($submissionReport['files']->isNotEmpty())
                                                        <div class="flex flex-wrap gap-2">
                                                            @foreach ($submissionReport['files'] as $file)
                                                                <a href="{{ $file['url'] }}" target="_blank" rel="noreferrer" class="rounded-full border border-violet-200 bg-violet-50 px-3 py-1 text-xs font-semibold text-violet-700 transition hover:bg-violet-100">
                                                                    {{ $file['name'] }}
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <span class="text-slate-500">-</span>
                                                    @endif
                                                </td>
                                                <td class="whitespace-nowrap px-6 py-4 align-top">
                                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                                        {{ $status['label'] }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="px-6 py-6 text-sm text-slate-500">No assignments have been created for this course.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </section>
                @else
                    <article class="rounded-[1.75rem] border border-slate-200 bg-white p-6 text-sm text-slate-500 shadow-sm">
                        No students are currently enrolled in this class.
                    </article>
                @endif
            </main>
        </div>
    </body>
</html>
