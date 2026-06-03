<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Campus LMS') }} | {{ $course->course_name }}</title>

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
                <a href="{{ route('teacher.dashboard') }}" class="group inline-flex items-center gap-3">
                    <span class="grid h-11 w-11 place-items-center rounded-2xl bg-slate-900 text-sm font-semibold text-white shadow-lg shadow-slate-900/20 transition-transform duration-200 group-hover:-translate-y-0.5">LMS</span>
                    <span class="block text-lg font-semibold text-slate-900">Course Content</span>
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
                    <h1 class="text-3xl font-semibold tracking-tight text-slate-950 lg:text-4xl">{{ $courseTitle }}</h1>
                    <nav class="mt-4 flex flex-wrap items-center gap-2 text-sm text-slate-500">
                        <a href="{{ route('teacher.dashboard') }}" class="font-medium text-slate-700 transition hover:text-slate-950">Dashboard</a>
                        <span>/</span>
                        <span class="font-medium text-slate-700">My courses</span>
                        <span>/</span>
                        <span class="font-semibold text-sky-700">{{ $courseCode }}</span>
                    </nav>
                </section>

                <section class="space-y-10">
                    @foreach ($meetings as $meeting)
                        <article class="space-y-5">
                            <div class="flex items-center gap-4">
                                <div class="h-px flex-1 bg-gradient-to-r from-transparent via-slate-300 to-slate-300"></div>
                                <div class="text-center">
                                    <h2 class="text-xl font-semibold tracking-tight text-slate-950">{{ $meeting['title'] }}</h2>
                                    <button type="button" data-open-activity-modal data-meeting="{{ $meeting['title'] }}" class="mt-3 rounded-full bg-slate-900 px-5 py-2 text-sm font-semibold text-white shadow-lg shadow-slate-900/20 transition hover:-translate-y-0.5 hover:bg-slate-800">Add</button>
                                </div>
                                <div class="h-px flex-1 bg-gradient-to-l from-transparent via-slate-300 to-slate-300"></div>
                            </div>

                            <div class="grid gap-4">
                                @foreach ($meeting['items'] as $item)
                                    <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm">
                                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ $item['type'] }}</p>
                                        <a href="#" class="mt-2 inline-flex text-lg font-semibold text-slate-950 transition hover:text-sky-700">{{ $item['title'] }}</a>
                                    </div>
                                @endforeach
                            </div>
                        </article>
                    @endforeach
                </section>
            </main>
        </div>

        <div class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 px-4 py-8" data-activity-modal>
            <div class="w-full max-w-2xl overflow-hidden rounded-[2rem] bg-white shadow-2xl shadow-slate-950/30">
                <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-700" data-modal-meeting-label>Pertemuan</p>
                        <h2 class="mt-2 text-2xl font-semibold tracking-tight text-slate-950">Add course activity</h2>
                    </div>
                    <button type="button" data-close-activity-modal class="rounded-full border border-slate-200 px-3 py-1 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">Close</button>
                </div>

                <form method="POST" action="#" enctype="multipart/form-data" class="space-y-5 px-6 py-6" data-activity-form>
                    @csrf
                    <input type="hidden" name="meeting" data-meeting-input>

                    <label class="block">
                        <span class="mb-2 block text-sm font-medium text-slate-700">Activity type</span>
                        <select name="activity_type" data-activity-type class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white">
                            <option value="submission">Submission</option>
                            <option value="attendance">Attendance</option>
                            <option value="materials">Materials</option>
                        </select>
                    </label>

                    <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800" data-activity-help></div>

                    <div class="grid gap-5" data-activity-fields></div>

                    <div class="flex justify-end gap-3">
                        <button type="button" data-close-activity-modal class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Cancel</button>
                        <button type="submit" data-activity-submit class="rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/20 transition hover:-translate-y-0.5 hover:bg-slate-800">Create Submission</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const modal = document.querySelector('[data-activity-modal]');
                const form = document.querySelector('[data-activity-form]');
                const typeSelect = document.querySelector('[data-activity-type]');
                const fields = document.querySelector('[data-activity-fields]');
                const help = document.querySelector('[data-activity-help]');
                const submit = document.querySelector('[data-activity-submit]');
                const meetingInput = document.querySelector('[data-meeting-input]');
                const meetingLabel = document.querySelector('[data-modal-meeting-label]');

                const configs = {
                    submission: {
                        action: @json(url('/dashboard-teacher/course/' . $course->id . '/submissions')),
                        help: 'Create a placeholder assignment submission activity. Database logic will be connected later.',
                        submit: 'Create Submission',
                        fields: `
                            <label class="block">
                                <span class="mb-2 block text-sm font-medium text-slate-700">Title</span>
                                <input type="text" name="title" placeholder="Pengumpulan project" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white">
                            </label>
                            <label class="block">
                                <span class="mb-2 block text-sm font-medium text-slate-700">Description</span>
                                <textarea name="description" rows="3" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white"></textarea>
                            </label>
                            <div class="grid gap-5 md:grid-cols-2">
                                <label class="block">
                                    <span class="mb-2 block text-sm font-medium text-slate-700">Deadline</span>
                                    <input type="datetime-local" name="deadline" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white">
                                </label>
                                <label class="block">
                                    <span class="mb-2 block text-sm font-medium text-slate-700">Allowed file types</span>
                                    <input type="text" name="allowed_file_types" placeholder="pdf, docx, zip" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white">
                                </label>
                            </div>
                        `,
                    },
                    attendance: {
                        action: @json(url('/dashboard-teacher/course/' . $course->id . '/attendance')),
                        help: 'Create an attendance window for a meeting. This is only the UI placeholder for now.',
                        submit: 'Create Attendance',
                        fields: `
                            <label class="block">
                                <span class="mb-2 block text-sm font-medium text-slate-700">Title</span>
                                <input type="text" name="title" placeholder="Daftar hadir pertemuan" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white">
                            </label>
                            <div class="grid gap-5 md:grid-cols-2">
                                <label class="block">
                                    <span class="mb-2 block text-sm font-medium text-slate-700">Open date/time</span>
                                    <input type="datetime-local" name="open_at" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white">
                                </label>
                                <label class="block">
                                    <span class="mb-2 block text-sm font-medium text-slate-700">Close date/time</span>
                                    <input type="datetime-local" name="close_at" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white">
                                </label>
                            </div>
                            <label class="block">
                                <span class="mb-2 block text-sm font-medium text-slate-700">Description</span>
                                <textarea name="description" rows="3" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white"></textarea>
                            </label>
                        `,
                    },
                    materials: {
                        action: @json(url('/dashboard-teacher/course/' . $course->id . '/materials')),
                        help: 'Upload or link a course material. File storage and material records will be added later.',
                        submit: 'Upload Material',
                        fields: `
                            <label class="block">
                                <span class="mb-2 block text-sm font-medium text-slate-700">Title</span>
                                <input type="text" name="title" placeholder="Materi 1" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white">
                            </label>
                            <label class="block">
                                <span class="mb-2 block text-sm font-medium text-slate-700">Description</span>
                                <textarea name="description" rows="3" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white"></textarea>
                            </label>
                            <div class="grid gap-5 md:grid-cols-2">
                                <label class="block">
                                    <span class="mb-2 block text-sm font-medium text-slate-700">File upload</span>
                                    <input type="file" name="material_file" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white">
                                </label>
                                <label class="block">
                                    <span class="mb-2 block text-sm font-medium text-slate-700">External link</span>
                                    <input type="url" name="external_link" placeholder="https://example.com/material" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white">
                                </label>
                            </div>
                        `,
                    },
                };

                const setType = () => {
                    const config = configs[typeSelect.value];
                    form.action = config.action;
                    help.textContent = config.help;
                    submit.textContent = config.submit;
                    fields.innerHTML = config.fields;
                };

                document.querySelectorAll('[data-open-activity-modal]').forEach((button) => {
                    button.addEventListener('click', () => {
                        meetingInput.value = button.dataset.meeting;
                        meetingLabel.textContent = button.dataset.meeting;
                        setType();
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    });
                });

                document.querySelectorAll('[data-close-activity-modal]').forEach((button) => {
                    button.addEventListener('click', () => {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    });
                });

                typeSelect.addEventListener('change', setType);
                setType();
            });
        </script>
    </body>
</html>
