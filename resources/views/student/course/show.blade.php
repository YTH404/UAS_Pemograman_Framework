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
            $courseCode = $course->classes?->class_code ?? $class?->class_code ?? 'MATAKULIAH';
            $courseTitle = $courseCode . '-' . $course->course_name;
        @endphp

        <div class="relative overflow-hidden">
            <div class="pointer-events-none absolute inset-0 -z-10 bg-[radial-gradient(circle_at_top_left,_rgba(14,165,233,0.16),_transparent_36%),radial-gradient(circle_at_top_right,_rgba(245,158,11,0.14),_transparent_28%),linear-gradient(to_bottom,_#f8fafc,_#eef2ff_48%,_#f8fafc)]"></div>

            <header class="mx-auto flex w-full max-w-7xl items-center justify-between px-6 py-5 lg:px-8">
                <a href="{{ route('dashboard') }}" class="group inline-flex items-center gap-3">
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
                        <a href="{{ route('dashboard') }}" class="font-medium text-slate-700 transition hover:text-slate-950">Dashboard</a>
                        <span>/</span>
                        <span class="font-medium text-slate-700">My courses</span>
                        <span>/</span>
                        <span class="font-semibold text-sky-700">{{ $courseCode }}</span>
                    </nav>
                </section>

                <section class="space-y-6">
                    @foreach ($meetings as $meeting)
                        @php
                            $hasMeetingContent = count($meeting['assignments']) > 0
                                || count($meeting['attendances']) > 0
                                || count($meeting['materials']) > 0;
                        @endphp

                        <article class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm">
                            <div class="border-b border-slate-200 bg-slate-50/70 px-6 py-5">
                                <h2 class="text-xl font-semibold tracking-tight text-slate-950">{{ $meeting['title'] }}</h2>
                            </div>

                            <div class="divide-y divide-slate-200">
                                @foreach ($meeting['assignments'] as $assignment)
                                    @php
                                        $assignmentStatusClass = match ($assignment['status']['variant']) {
                                            'success' => 'bg-emerald-100 text-emerald-700',
                                            'danger' => 'bg-rose-100 text-rose-700',
                                            'action' => 'border border-violet-300 bg-white text-violet-700 hover:bg-violet-50',
                                            default => 'bg-slate-100 text-slate-600',
                                        };
                                    @endphp

                                    <div class="p-6">
                                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                            <div>
                                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-violet-700">Assignment · {{ $assignment['assignment_type_label'] }}</p>
                                                <h3 class="mt-2 text-lg font-semibold text-slate-950">{{ $assignment['title'] }}</h3>
                                                @if ($assignment['description'])
                                                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ $assignment['description'] }}</p>
                                                @endif
                                                <p class="mt-2 text-sm leading-6 text-slate-600">
                                                    Opens: {{ $assignment['started_at']?->format('d M Y H:i') ?? '-' }} ·
                                                    Closes: {{ $assignment['ended_at']?->format('d M Y H:i') ?? '-' }}
                                                </p>

                                                @if ($assignment['files']->isNotEmpty())
                                                    <div class="mt-3 flex flex-wrap gap-2">
                                                        @foreach ($assignment['files'] as $file)
                                                            <a href="{{ $file['url'] }}" target="_blank" class="rounded-full bg-violet-50 px-3 py-1 text-xs font-semibold text-violet-700 transition hover:bg-violet-100">{{ $file['name'] }}</a>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                @if ($assignment['submitted_at'] !== null)
                                                    <p class="mt-3 text-sm font-semibold text-violet-700">
                                                        {{ $assignment['grade'] !== null ? 'Grade: ' . $assignment['grade'] . '/100' : 'Not graded yet' }}
                                                    </p>
                                                @endif
                                            </div>

                                            <div class="flex flex-col items-start gap-2 sm:items-end">
                                                @if ($assignment['status']['can_submit'])
                                                    <button
                                                        type="button"
                                                        data-open-assignment-modal
                                                        data-action="{{ $assignment['submit_url'] }}"
                                                        data-title="{{ $assignment['title'] }}"
                                                        data-started-at="{{ $assignment['started_at']?->format('d M Y H:i') ?? '-' }}"
                                                        data-ended-at="{{ $assignment['ended_at']?->format('d M Y H:i') ?? '-' }}"
                                                        data-button-label="{{ $assignment['status']['button_label'] }}"
                                                        class="inline-flex w-fit items-center rounded-full px-4 py-2 text-sm font-semibold transition {{ $assignmentStatusClass }}"
                                                    >
                                                        {{ $assignment['status']['button_label'] }}
                                                    </button>
                                                @else
                                                    <span class="inline-flex w-fit items-center rounded-full px-4 py-2 text-sm font-semibold {{ $assignmentStatusClass }}">
                                                        {{ $assignment['status']['label'] }}
                                                    </span>
                                                @endif

                                                @if ($assignment['done_mark']['toggle_url'])
                                                    <form method="POST" action="{{ $assignment['done_mark']['toggle_url'] }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="inline-flex w-fit items-center rounded-full px-4 py-2 text-sm font-semibold transition {{ $assignment['done_mark']['is_done'] ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'border border-slate-300 bg-white text-slate-700 hover:border-violet-300 hover:bg-violet-50 hover:text-violet-700' }}">
                                                            {{ $assignment['done_mark']['is_done'] ? '✓ Done' : 'Mark Done' }}
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                @foreach ($meeting['attendances'] as $attendance)
                                    @php
                                        $statusClass = match ($attendance['status']['variant']) {
                                            'success' => 'bg-emerald-100 text-emerald-700',
                                            'danger' => 'bg-rose-100 text-rose-700',
                                            'action' => 'border border-sky-300 bg-white text-sky-700 hover:bg-sky-50',
                                            default => 'bg-slate-100 text-slate-600',
                                        };
                                    @endphp

                                    <div class="p-6">
                                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                            <div>
                                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">Attendance</p>
                                                <h3 class="mt-2 text-lg font-semibold text-slate-950">{{ $attendance['title'] }}</h3>
                                                <p class="mt-2 text-sm leading-6 text-slate-600">
                                                    Opens: {{ $attendance['started_at']?->format('d M Y H:i') ?? '-' }} ·
                                                    Closes: {{ $attendance['ended_at']?->format('d M Y H:i') ?? '-' }}
                                                </p>
                                            </div>

                                            <div class="flex flex-col items-start gap-2 sm:items-end">
                                                @if ($attendance['status']['can_fill'])
                                                    <button
                                                        type="button"
                                                        data-open-attendance-modal
                                                        data-action="{{ $attendance['fill_url'] }}"
                                                        data-title="{{ $attendance['title'] }}"
                                                        data-started-at="{{ $attendance['started_at']?->format('d M Y H:i') ?? '-' }}"
                                                        data-ended-at="{{ $attendance['ended_at']?->format('d M Y H:i') ?? '-' }}"
                                                        class="inline-flex w-fit items-center rounded-full px-4 py-2 text-sm font-semibold transition {{ $statusClass }}"
                                                    >
                                                        {{ $attendance['status']['label'] }}
                                                    </button>
                                                @else
                                                    <span class="inline-flex w-fit items-center rounded-full px-4 py-2 text-sm font-semibold {{ $statusClass }}">
                                                        {{ $attendance['status']['label'] }}
                                                    </span>
                                                @endif

                                                @if ($attendance['done_mark']['toggle_url'])
                                                    <form method="POST" action="{{ $attendance['done_mark']['toggle_url'] }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="inline-flex w-fit items-center rounded-full px-4 py-2 text-sm font-semibold transition {{ $attendance['done_mark']['is_done'] ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'border border-slate-300 bg-white text-slate-700 hover:border-sky-300 hover:bg-sky-50 hover:text-sky-700' }}">
                                                            {{ $attendance['done_mark']['is_done'] ? '✓ Done' : 'Mark Done' }}
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                @foreach ($meeting['materials'] as $materialCard)
                                    @php
                                        $material = $materialCard['model'];
                                        $doneMark = $materialCard['done_mark'];
                                    @endphp

                                    <div class="p-6">
                                        <div class="space-y-4">
                                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                                <div>
                                                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Material · {{ ['document' => 'Document', 'video' => 'Video', 'link' => 'Link'][$material->material_type] ?? ucfirst($material->material_type) }}</p>
                                                    @if ($material->material_type === 'document')
                                                        <a href="{{ $material->fileUrl() }}" target="_blank" class="mt-2 inline-flex text-lg font-semibold text-slate-950 transition hover:text-sky-700">{{ $material->title }}</a>
                                                    @elseif ($material->external_link)
                                                        <a href="{{ $material->external_link }}" target="_blank" class="mt-2 inline-flex text-lg font-semibold text-slate-950 transition hover:text-sky-700">{{ $material->title }}</a>
                                                    @else
                                                        <span class="mt-2 inline-flex text-lg font-semibold text-slate-950">{{ $material->title }}</span>
                                                    @endif

                                                    @if ($material->description)
                                                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ $material->description }}</p>
                                                    @endif
                                                </div>

                                                @if ($doneMark['toggle_url'])
                                                    <form method="POST" action="{{ $doneMark['toggle_url'] }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="inline-flex w-fit items-center rounded-full px-4 py-2 text-sm font-semibold transition {{ $doneMark['is_done'] ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'border border-slate-300 bg-white text-slate-700 hover:border-sky-300 hover:bg-sky-50 hover:text-sky-700' }}">
                                                            {{ $doneMark['is_done'] ? '✓ Done' : 'Mark Done' }}
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>

                                            @if ($material->youtubeEmbedUrl())
                                                <div class="aspect-video w-full max-w-2xl overflow-hidden rounded-2xl border border-slate-200 bg-slate-950">
                                                    <iframe src="{{ $material->youtubeEmbedUrl() }}" title="{{ $material->title }}" class="h-full w-full" allowfullscreen></iframe>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach

                                @unless ($hasMeetingContent)
                                    <div class="p-6 text-sm text-slate-500">No content has been added for this week yet.</div>
                                @endunless
                            </div>
                        </article>
                    @endforeach
                </section>
            </main>
        </div>

        <div class="fixed inset-0 z-50 hidden items-center justify-center overflow-y-auto overflow-x-clip bg-slate-950/60 px-4 py-8" data-attendance-modal>
            <div class="w-full max-w-[calc(100vw-2rem)] rounded-[2rem] bg-white shadow-2xl shadow-slate-950/30 sm:max-w-lg">
                <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-emerald-700">Attendance</p>
                        <h2 class="mt-2 text-2xl font-semibold tracking-tight text-slate-950" data-attendance-title>Fill attendance</h2>
                    </div>
                    <button type="button" data-close-attendance-modal class="rounded-full border border-slate-200 px-3 py-1 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">Close</button>
                </div>

                <form method="POST" action="#" class="space-y-5 px-6 py-6" data-attendance-form>
                    @csrf
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm leading-6 text-emerald-800">
                        Confirming this form will mark your attendance as present. Attendance can only be filled once.
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                        <p><span class="font-semibold text-slate-800">Opens:</span> <span data-attendance-started-at>-</span></p>
                        <p><span class="font-semibold text-slate-800">Closes:</span> <span data-attendance-ended-at>-</span></p>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" data-close-attendance-modal class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Cancel</button>
                        <button type="submit" class="rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/20 transition hover:-translate-y-0.5 hover:bg-slate-800">Confirm Attendance</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="fixed inset-0 z-50 hidden items-center justify-center overflow-y-auto overflow-x-clip bg-slate-950/60 px-4 py-8" data-assignment-modal>
            <div class="w-full max-w-[calc(100vw-2rem)] rounded-[2rem] bg-white shadow-2xl shadow-slate-950/30 sm:max-w-lg">
                <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-violet-700">Assignment</p>
                        <h2 class="mt-2 text-2xl font-semibold tracking-tight text-slate-950" data-assignment-title>Kumpulkan assignment</h2>
                    </div>
                    <button type="button" data-close-assignment-modal class="rounded-full border border-slate-200 px-3 py-1 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">Close</button>
                </div>

                <form method="POST" action="#" enctype="multipart/form-data" class="space-y-5 px-6 py-6" data-assignment-form>
                    @csrf
                    <div class="rounded-2xl border border-violet-200 bg-violet-50 px-4 py-3 text-sm leading-6 text-violet-800">
                        Upload up to 5 files, each up to 10MB. If you have already submitted, these files will replace the previous files.
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                        <p><span class="font-semibold text-slate-800">Opens:</span> <span data-assignment-started-at>-</span></p>
                        <p><span class="font-semibold text-slate-800">Closes:</span> <span data-assignment-ended-at>-</span></p>
                    </div>

                    <label class="block">
                        <span class="mb-2 block text-sm font-medium text-slate-700">File</span>
                        <input type="file" name="files[]" multiple required class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-violet-500 focus:bg-white">
                    </label>

                    <div class="flex justify-end gap-3">
                        <button type="button" data-close-assignment-modal class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Cancel</button>
                        <button type="submit" data-assignment-submit class="rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/20 transition hover:-translate-y-0.5 hover:bg-slate-800">Submit Assignment</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const modal = document.querySelector('[data-attendance-modal]');
                const form = document.querySelector('[data-attendance-form]');
                const title = document.querySelector('[data-attendance-title]');
                const startedAt = document.querySelector('[data-attendance-started-at]');
                const endedAt = document.querySelector('[data-attendance-ended-at]');

                if (!modal || !form || !title || !startedAt || !endedAt) {
                    return;
                }

                const openModal = () => {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    document.body.classList.add('overflow-hidden');
                };

                const closeModal = () => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    document.body.classList.remove('overflow-hidden');
                };

                document.querySelectorAll('[data-open-attendance-modal]').forEach((button) => {
                    button.addEventListener('click', () => {
                        form.action = button.dataset.action;
                        title.textContent = button.dataset.title || 'Fill attendance';
                        startedAt.textContent = button.dataset.startedAt || '-';
                        endedAt.textContent = button.dataset.endedAt || '-';
                        openModal();
                    });
                });

                document.querySelectorAll('[data-close-attendance-modal]').forEach((button) => {
                    button.addEventListener('click', closeModal);
                });
            });

            document.addEventListener('DOMContentLoaded', () => {
                const modal = document.querySelector('[data-assignment-modal]');
                const form = document.querySelector('[data-assignment-form]');
                const title = document.querySelector('[data-assignment-title]');
                const startedAt = document.querySelector('[data-assignment-started-at]');
                const endedAt = document.querySelector('[data-assignment-ended-at]');
                const submit = document.querySelector('[data-assignment-submit]');

                if (!modal || !form || !title || !startedAt || !endedAt || !submit) {
                    return;
                }

                const openModal = () => {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    document.body.classList.add('overflow-hidden');
                };

                const closeModal = () => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    document.body.classList.remove('overflow-hidden');
                };

                document.querySelectorAll('[data-open-assignment-modal]').forEach((button) => {
                    button.addEventListener('click', () => {
                        form.reset();
                        form.action = button.dataset.action;
                        title.textContent = button.dataset.title || 'Kumpulkan assignment';
                        startedAt.textContent = button.dataset.startedAt || '-';
                        endedAt.textContent = button.dataset.endedAt || '-';
                        submit.textContent = button.dataset.buttonLabel || 'Submit Assignment';
                        openModal();
                    });
                });

                document.querySelectorAll('[data-close-assignment-modal]').forEach((button) => {
                    button.addEventListener('click', closeModal);
                });
            });
        </script>
    </body>
</html>
