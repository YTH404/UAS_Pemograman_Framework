<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Campus LMS') }} | Nilai Tugas</title>

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
        <x-sweet-alert-messages />

        @php
            $courseCode = $course->classes?->class_code ?? 'MATAKULIAH';
            $courseTitle = $courseCode . '-' . $course->course_name;
        @endphp

        <div class="relative overflow-hidden">
            <div class="pointer-events-none absolute inset-0 -z-10 bg-[radial-gradient(circle_at_top_left,_rgba(14,165,233,0.16),_transparent_36%),radial-gradient(circle_at_top_right,_rgba(245,158,11,0.14),_transparent_28%),linear-gradient(to_bottom,_#f8fafc,_#eef2ff_48%,_#f8fafc)]"></div>

            <header class="mx-auto flex w-full max-w-7xl items-center justify-between px-6 py-5 lg:px-8">
                <a href="{{ route('teacher.course.show', $course->id) }}" class="group inline-flex items-center gap-3">
                    <span class="grid h-11 w-11 place-items-center rounded-2xl bg-slate-900 text-sm font-semibold text-white shadow-lg shadow-slate-900/20 transition-transform duration-200 group-hover:-translate-y-0.5">LMS</span>
                    <span>
                        <span class="block text-lg font-semibold text-slate-900">Nilai Tugas</span>
                        <span class="block text-xs font-medium uppercase tracking-[0.2em] text-slate-500">Kembali ke mata kuliah</span>
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
                    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-violet-700">{{ $assignment->meeting }}</p>
                    <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950 lg:text-4xl">{{ $assignment->title }}</h1>
                    <p class="mt-3 text-sm leading-6 text-slate-600">{{ $courseTitle }}</p>

                    <nav class="mt-4 flex flex-wrap items-center gap-2 text-sm text-slate-500">
                        <a href="{{ route('teacher.dashboard') }}" class="font-medium text-slate-700 transition hover:text-slate-950">Beranda</a>
                        <span>/</span>
                        <a href="{{ route('teacher.course.show', $course->id) }}" class="font-medium text-slate-700 transition hover:text-slate-950">Mata kuliah saya</a>
                        <span>/</span>
                        <span class="font-semibold text-violet-700">Nilai</span>
                    </nav>

                    <div class="mt-8 grid gap-4 md:grid-cols-3">
                        <div class="rounded-3xl bg-slate-950 p-5 text-white shadow-sm ring-1 ring-white/10">
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-sky-300">Mahasiswa</p>
                            <p class="mt-4 text-4xl font-semibold">{{ $summary['total'] }}</p>
                            <p class="mt-3 text-sm leading-6 text-slate-300">Mahasiswa di kelas mata kuliah ini.</p>
                        </div>
                        <div class="rounded-3xl bg-slate-950 p-5 text-white shadow-sm ring-1 ring-white/10">
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-sky-300">Terkumpul</p>
                            <p class="mt-4 text-4xl font-semibold">{{ $summary['submitted'] }}</p>
                            <p class="mt-3 text-sm leading-6 text-slate-300">Mahasiswa yang sudah mengunggah file.</p>
                        </div>
                        <div class="rounded-3xl bg-slate-950 p-5 text-white shadow-sm ring-1 ring-white/10">
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-sky-300">Dinilai</p>
                            <p class="mt-4 text-4xl font-semibold">{{ $summary['graded'] }}</p>
                            <p class="mt-3 text-sm leading-6 text-slate-300">Pengumpulan yang sudah memiliki nilai.</p>
                        </div>
                    </div>
                </section>

                <div class="flex items-center gap-4">
                    <div class="h-px flex-1 bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>
                    <span class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Pengumpulan Mahasiswa</span>
                    <div class="h-px flex-1 bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>
                </div>

                <section class="space-y-4">
                    @forelse ($studentSubmissions as $item)
                        @php
                            $student = $item['student'];
                            $submission = $item['submission'];
                            $hasSubmitted = $submission?->submitted_at !== null;
                        @endphp

                        <article class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ $student->username }}</p>
                                    <h2 class="mt-2 text-xl font-semibold text-slate-950">{{ $student->name }}</h2>
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        @if ($hasSubmitted)
                                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                                Dikumpulkan {{ $submission->submitted_at?->format('d M Y H:i') }}
                                            </span>
                                            <span class="rounded-full bg-violet-100 px-3 py-1 text-xs font-semibold text-violet-700">
                                                {{ $submission->grade !== null ? 'Nilai: ' . $submission->grade . '/100' : 'Belum dinilai' }}
                                            </span>
                                        @else
                                            <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700">Belum mengumpulkan</span>
                                        @endif
                                    </div>

                                    @if ($hasSubmitted && $submission->files->isNotEmpty())
                                        <div class="mt-4 flex flex-wrap gap-2">
                                            @foreach ($submission->files as $file)
                                                <a href="{{ $file->fileUrl() }}" target="_blank" class="rounded-full border border-violet-200 bg-violet-50 px-3 py-1 text-xs font-semibold text-violet-700 transition hover:bg-violet-100">
                                                    {{ $file->original_name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @elseif ($hasSubmitted)
                                        <p class="mt-4 text-sm text-slate-500">Sudah dikumpulkan, tetapi tidak ada file terlampir.</p>
                                    @else
                                        <p class="mt-4 text-sm text-slate-500">Mahasiswa ini belum mengunggah file untuk tugas ini.</p>
                                    @endif
                                </div>

                                <div class="flex shrink-0 flex-wrap gap-2 lg:justify-end">
                                    @if ($hasSubmitted)
                                        <button
                                            type="button"
                                            data-open-grade-modal
                                            data-action="{{ route('teacher.course.assignments.submissions.grade', [$course->id, $assignment->id, $submission->id]) }}"
                                            data-student="{{ $student->name }}"
                                            data-username="{{ $student->username }}"
                                            data-grade="{{ $submission->grade }}"
                                            data-submitted-at="{{ $submission->submitted_at?->format('d M Y H:i') }}"
                                            class="rounded-full bg-slate-900 px-5 py-2 text-sm font-semibold text-white shadow-lg shadow-slate-900/20 transition hover:-translate-y-0.5 hover:bg-slate-800"
                                        >
                                            {{ $submission->grade !== null ? 'Ubah Nilai' : 'Beri Nilai' }}
                                        </button>
                                    @else
                                        <span class="rounded-full border border-slate-200 bg-slate-100 px-5 py-2 text-sm font-semibold text-slate-500">Penilaian nonaktif</span>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @empty
                        <article class="rounded-[1.75rem] border border-slate-200 bg-white p-6 text-sm text-slate-500 shadow-sm">
                            Belum ada mahasiswa pada kelas mata kuliah ini.
                        </article>
                    @endforelse
                </section>
            </main>
        </div>

        <div class="fixed inset-0 z-50 hidden items-center justify-center overflow-y-auto overflow-x-clip bg-slate-950/60 px-4 py-8" data-grade-modal>
            <div class="w-full max-w-[calc(100vw-2rem)] rounded-[2rem] bg-white shadow-2xl shadow-slate-950/30 sm:max-w-lg">
                <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-violet-700">Nilai Tugas</p>
                        <h2 class="mt-2 text-2xl font-semibold tracking-tight text-slate-950" data-grade-student>Beri nilai</h2>
                    </div>
                    <button type="button" data-close-grade-modal class="rounded-full border border-slate-200 px-3 py-1 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">Tutup</button>
                </div>

                <form method="POST" action="#" class="space-y-5 px-6 py-6" data-grade-form>
                    @csrf
                    @method('PATCH')

                    <div class="rounded-2xl border border-violet-200 bg-violet-50 px-4 py-3 text-sm leading-6 text-violet-800">
                        Masukkan nilai bilangan bulat dari 0 sampai 100. Kosongkan untuk menghapus nilai saat ini.
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                        <p><span class="font-semibold text-slate-800">Nama pengguna:</span> <span data-grade-username>-</span></p>
                        <p><span class="font-semibold text-slate-800">Dikumpulkan:</span> <span data-grade-submitted-at>-</span></p>
                    </div>

                    <label class="block">
                        <span class="mb-2 block text-sm font-medium text-slate-700">Nilai</span>
                        <input type="number" name="grade" min="0" max="100" step="1" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-violet-500 focus:bg-white" data-grade-input>
                    </label>

                    <div class="flex justify-end gap-3">
                        <button type="button" data-close-grade-modal class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Batal</button>
                        <button type="submit" class="rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/20 transition hover:-translate-y-0.5 hover:bg-slate-800">Simpan Nilai</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const modal = document.querySelector('[data-grade-modal]');
                const form = document.querySelector('[data-grade-form]');
                const student = document.querySelector('[data-grade-student]');
                const username = document.querySelector('[data-grade-username]');
                const submittedAt = document.querySelector('[data-grade-submitted-at]');
                const grade = document.querySelector('[data-grade-input]');

                if (!modal || !form || !student || !username || !submittedAt || !grade) {
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

                document.querySelectorAll('[data-open-grade-modal]').forEach((button) => {
                    button.addEventListener('click', () => {
                        form.action = button.dataset.action;
                        student.textContent = button.dataset.student || 'Beri nilai';
                        username.textContent = button.dataset.username || '-';
                        submittedAt.textContent = button.dataset.submittedAt || '-';
                        grade.value = button.dataset.grade || '';
                        openModal();
                    });
                });

                document.querySelectorAll('[data-close-grade-modal]').forEach((button) => {
                    button.addEventListener('click', closeModal);
                });

                modal.addEventListener('click', (event) => {
                    if (event.target === modal) {
                        closeModal();
                    }
                });
            });
        </script>
    </body>
</html>
