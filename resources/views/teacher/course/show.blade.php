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
            $courseCode = $course->classes?->class_code ?? 'MATAKULIAH';
            $courseTitle = $courseCode . '-' . $course->course_name;
        @endphp

        <div class="relative overflow-hidden">
            <div class="pointer-events-none absolute inset-0 -z-10 bg-[radial-gradient(circle_at_top_left,_rgba(14,165,233,0.16),_transparent_36%),radial-gradient(circle_at_top_right,_rgba(245,158,11,0.14),_transparent_28%),linear-gradient(to_bottom,_#f8fafc,_#eef2ff_48%,_#f8fafc)]"></div>

            <header class="mx-auto flex w-full max-w-7xl items-center justify-between px-6 py-5 lg:px-8">
                <a href="{{ route('teacher.dashboard') }}" class="group inline-flex items-center gap-3">
                    <span class="grid h-11 w-11 place-items-center rounded-2xl bg-slate-900 text-sm font-semibold text-white shadow-lg shadow-slate-900/20 transition-transform duration-200 group-hover:-translate-y-0.5">LMS</span>
                    <span class="block text-lg font-semibold text-slate-900">Konten Mata Kuliah</span>
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
                        <a href="{{ route('teacher.dashboard') }}" class="font-medium text-slate-700 transition hover:text-slate-950">Beranda</a>
                        <span>/</span>
                        <span class="font-medium text-slate-700">Mata kuliah saya</span>
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
                                    @if ($meeting['can_add'])
                                        <button type="button" data-open-activity-modal data-meeting="{{ $meeting['title'] }}" class="mt-3 rounded-full bg-slate-900 px-5 py-2 text-sm font-semibold text-white shadow-lg shadow-slate-900/20 transition hover:-translate-y-0.5 hover:bg-slate-800">Tambah</button>
                                    @else
                                        <span class="mt-3 inline-flex rounded-full border border-slate-200 bg-slate-100 px-5 py-2 text-sm font-semibold text-slate-500">Terkunci</span>
                                    @endif
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

                                @foreach ($meeting['assignments'] as $assignment)
                                    <div class="rounded-[1.5rem] border border-violet-200 bg-violet-50/70 p-5 shadow-sm">
                                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                            <div>
                                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-violet-700">Tugas</p>
                                                <h3 class="mt-2 text-lg font-semibold text-slate-950">{{ $assignment['title'] }}</h3>
                                                @if ($assignment['description'])
                                                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ $assignment['description'] }}</p>
                                                @endif
                                                <p class="mt-2 text-sm leading-6 text-slate-600">
                                                    Buka: {{ $assignment['started_at']?->format('d M Y H:i') ?? '-' }} ·
                                                    Tutup: {{ $assignment['ended_at']?->format('d M Y H:i') ?? '-' }}
                                                </p>
                                            </div>

                                            <div class="flex flex-wrap items-center gap-2">
                                                <span class="inline-flex w-fit items-center rounded-full bg-white px-4 py-2 text-sm font-semibold text-violet-700 shadow-sm">
                                                    {{ $assignment['submitted_count'] }}/{{ $assignment['total_count'] }} terkumpul
                                                </span>
                                                <span class="inline-flex w-fit items-center rounded-full bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm">
                                                    {{ $assignment['graded_count'] }}/{{ $assignment['total_count'] }} dinilai
                                                </span>
                                                <button
                                                    type="button"
                                                    data-edit-assignment-modal
                                                    data-action="{{ $assignment['update_url'] }}"
                                                    data-meeting="{{ $meeting['title'] }}"
                                                    data-title="{{ $assignment['title'] }}"
                                                    data-description="{{ $assignment['description'] }}"
                                                    data-started-at="{{ $assignment['started_at']?->format('Y-m-d\TH:i') }}"
                                                    data-ended-at="{{ $assignment['ended_at']?->format('Y-m-d\TH:i') }}"
                                                    class="rounded-full bg-white px-4 py-2 text-sm font-semibold text-sky-700 shadow-sm transition hover:bg-sky-50"
                                                >
                                                    Ubah
                                                </button>
                                                <a href="{{ $assignment['grade_url'] }}" class="rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                                                    Nilai
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                @foreach ($meeting['attendances'] as $attendance)
                                    <div class="rounded-[1.5rem] border border-emerald-200 bg-emerald-50/70 p-5 shadow-sm">
                                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                            <div>
                                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">Presensi</p>
                                                <h3 class="mt-2 text-lg font-semibold text-slate-950">{{ $attendance['title'] }}</h3>
                                                <p class="mt-2 text-sm leading-6 text-slate-600">
                                                    Buka: {{ $attendance['started_at']?->format('d M Y H:i') ?? '-' }} ·
                                                    Tutup: {{ $attendance['ended_at']?->format('d M Y H:i') ?? '-' }}
                                                </p>
                                            </div>

                                            <div class="flex flex-wrap items-center gap-2">
                                                <span class="inline-flex w-fit items-center rounded-full bg-white px-4 py-2 text-sm font-semibold text-emerald-700 shadow-sm">
                                                    {{ $attendance['filled_count'] }}/{{ $attendance['total_count'] }} terisi
                                                </span>
                                                <button
                                                    type="button"
                                                    data-edit-attendance-modal
                                                    data-action="{{ $attendance['update_url'] }}"
                                                    data-meeting="{{ $meeting['title'] }}"
                                                    data-title="{{ $attendance['title'] }}"
                                                    data-started-at="{{ $attendance['started_at']?->format('Y-m-d\TH:i') }}"
                                                    data-ended-at="{{ $attendance['ended_at']?->format('Y-m-d\TH:i') }}"
                                                    class="rounded-full bg-white px-4 py-2 text-sm font-semibold text-sky-700 shadow-sm transition hover:bg-sky-50"
                                                >
                                                    Ubah
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                @foreach ($meeting['materials'] as $material)
                                    <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm">
                                        <div class="space-y-4">
                                            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                                <div>
                                                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Materi · {{ ['document' => 'Dokumen', 'video' => 'Video', 'link' => 'Tautan'][$material->material_type] ?? ucfirst($material->material_type) }}</p>
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

                                                <div class="flex gap-2">
                                                    <button
                                                        type="button"
                                                        data-edit-material-modal
                                                        data-action="{{ route('teacher.course.materials.update', [$course->id, $material->id]) }}"
                                                        data-meeting="{{ $material->meeting }}"
                                                        data-title="{{ $material->title }}"
                                                        data-description="{{ $material->description }}"
                                                        data-material-type="{{ $material->material_type }}"
                                                        data-external-link="{{ $material->external_link }}"
                                                        class="rounded-lg px-3 py-2 text-xs font-semibold text-sky-700 transition-colors hover:bg-sky-100"
                                                    >
                                                        Ubah
                                                    </button>
                                                    <form method="POST" action="{{ route('teacher.course.materials.destroy', [$course->id, $material->id]) }}" data-swal-delete data-swal-title="Hapus materi?" data-swal-text="Ini akan menghapus {{ $material->title }}." data-swal-confirm="Ya, hapus">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="rounded-lg px-3 py-2 text-xs font-semibold text-red-600 transition-colors hover:bg-red-100">Hapus</button>
                                                    </form>
                                                </div>
                                            </div>

                                            @if ($material->youtubeEmbedUrl())
                                                <div class="aspect-video overflow-hidden rounded-2xl border border-slate-200 bg-slate-950">
                                                    <iframe src="{{ $material->youtubeEmbedUrl() }}" title="{{ $material->title }}" class="h-full w-full" allowfullscreen></iframe>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </article>
                    @endforeach
                </section>
            </main>
        </div>

        <div class="fixed inset-0 z-50 hidden items-start justify-center overflow-y-auto overflow-x-clip bg-slate-950/60 px-4 py-8" data-activity-modal>
            <div class="my-auto max-h-[calc(100vh-4rem)] w-full max-w-[calc(100vw-2rem)] overflow-y-auto overflow-x-clip rounded-[2rem] bg-white shadow-2xl shadow-slate-950/30 sm:max-w-2xl">
                <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-700" data-modal-meeting-label>Pertemuan</p>
                        <h2 class="mt-2 text-2xl font-semibold tracking-tight text-slate-950" data-modal-title>Tambah aktivitas mata kuliah</h2>
                    </div>
                    <button type="button" data-close-activity-modal class="rounded-full border border-slate-200 px-3 py-1 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">Tutup</button>
                </div>

                <form method="POST" action="#" enctype="multipart/form-data" class="min-w-0 space-y-5 px-6 py-6" data-activity-form>
                    @csrf
                    <div data-form-method></div>
                    <input type="hidden" name="meeting" data-meeting-input>

                    <label class="block" data-activity-type-wrapper>
                        <span class="mb-2 block text-sm font-medium text-slate-700">Jenis aktivitas</span>
                        <select name="activity_type" data-activity-type class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white">
                            <option value="submission">Tugas</option>
                            <option value="attendance">Presensi</option>
                            <option value="materials">Materi</option>
                        </select>
                    </label>

                    {{-- <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800" data-activity-help></div> --}}

                    <div class="grid gap-5" data-activity-fields></div>

                    <div class="flex justify-end gap-3">
                        <button type="button" data-close-activity-modal class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Batal</button>
                        <button type="submit" data-activity-submit class="rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/20 transition hover:-translate-y-0.5 hover:bg-slate-800">Buat Tugas</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const modal = document.querySelector('[data-activity-modal]');
                const form = document.querySelector('[data-activity-form]');
                const methodTarget = document.querySelector('[data-form-method]');
                const typeWrapper = document.querySelector('[data-activity-type-wrapper]');
                const typeSelect = document.querySelector('[data-activity-type]');
                const fields = document.querySelector('[data-activity-fields]');
                const help = document.querySelector('[data-activity-help]');
                const submit = document.querySelector('[data-activity-submit]');
                const meetingInput = document.querySelector('[data-meeting-input]');
                const meetingLabel = document.querySelector('[data-modal-meeting-label]');
                const modalTitle = document.querySelector('[data-modal-title]');

                let mode = 'create';
                let currentMaterial = {};
                let currentAttendance = {};
                let currentAssignment = {};

                const escapeHtml = (value = '') =>
                    String(value)
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#039;');

                const setMethod = (method = null) => {
                    methodTarget.innerHTML = method ? `<input type="hidden" name="_method" value="${method}">` : '';
                };

                const materialSourceFields = (materialType) => {
                    if (materialType === 'document') {
                        return `
                            <label class="block">
                                <span class="mb-2 block text-sm font-medium text-slate-700">${mode === 'edit' ? 'Ganti file dokumen' : 'File dokumen'}</span>
                                <input type="file" name="file_path" ${mode === 'create' ? 'required' : ''} class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white">
                                ${mode === 'edit' ? '<p class="mt-2 text-xs text-slate-500">Kosongkan untuk mempertahankan dokumen saat ini.</p>' : ''}
                            </label>
                        `;
                    }

                    return `
                        <label class="block">
                            <span class="mb-2 block text-sm font-medium text-slate-700">${materialType === 'video' ? 'URL video' : 'Tautan eksternal'}</span>
                            <input type="url" name="external_link" value="${escapeHtml(currentMaterial.externalLink)}" placeholder="${materialType === 'video' ? 'https://www.youtube.com/watch?v=...' : 'https://example.com/material'}" required class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white">
                            ${materialType === 'video' ? '<p class="mt-2 text-xs text-slate-500">Tautan YouTube akan ditampilkan sebagai pratinjau video di halaman.</p>' : ''}
                        </label>
                    `;
                };

                const bindMaterialTypeSelect = () => {
                    const materialTypeSelect = fields.querySelector('[data-material-kind]');
                    const sourceFields = fields.querySelector('[data-material-source-fields]');

                    if (!materialTypeSelect || !sourceFields) {
                        return;
                    }

                    const renderSource = () => {
                        sourceFields.innerHTML = materialSourceFields(materialTypeSelect.value);
                    };

                    materialTypeSelect.addEventListener('change', () => {
                        currentMaterial.externalLink = '';
                        renderSource();
                    });

                    renderSource();
                };

                const materialFields = () => {
                    const materialType = currentMaterial.materialType || 'document';

                    return `
                        <label class="block">
                            <span class="mb-2 block text-sm font-medium text-slate-700">Jenis materi</span>
                            <select name="material_type" data-material-kind class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white">
                                <option value="document" ${materialType === 'document' ? 'selected' : ''}>Dokumen</option>
                                <option value="video" ${materialType === 'video' ? 'selected' : ''}>Video</option>
                                <option value="link" ${materialType === 'link' ? 'selected' : ''}>Tautan</option>
                            </select>
                        </label>
                        <label class="block">
                            <span class="mb-2 block text-sm font-medium text-slate-700">Judul</span>
                            <input type="text" name="title" value="${escapeHtml(currentMaterial.title)}" placeholder="Materi 1" required class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white">
                        </label>
                        <label class="block">
                            <span class="mb-2 block text-sm font-medium text-slate-700">Deskripsi</span>
                            <textarea name="description" rows="3" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white">${escapeHtml(currentMaterial.description)}</textarea>
                        </label>
                        <div data-material-source-fields></div>
                    `;
                };

                const attendanceFields = () => `
                    <label class="block">
                        <span class="mb-2 block text-sm font-medium text-slate-700">Judul</span>
                        <input type="text" name="title" value="${escapeHtml(currentAttendance.title)}" placeholder="Daftar hadir pertemuan" required class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white">
                    </label>
                    <div class="grid gap-5 md:grid-cols-2">
                        <label class="block">
                            <span class="mb-2 block text-sm font-medium text-slate-700">Tanggal/waktu buka</span>
                            <input type="datetime-local" name="started_at" value="${escapeHtml(currentAttendance.startedAt)}" required class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white">
                        </label>
                        <label class="block">
                            <span class="mb-2 block text-sm font-medium text-slate-700">Tanggal/waktu tutup</span>
                            <input type="datetime-local" name="ended_at" value="${escapeHtml(currentAttendance.endedAt)}" required class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white">
                        </label>
                    </div>
                `;

                const assignmentFields = () => `
                    <label class="block">
                        <span class="mb-2 block text-sm font-medium text-slate-700">Judul</span>
                        <input type="text" name="title" value="${escapeHtml(currentAssignment.title)}" placeholder="Pengumpulan project" required class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white">
                    </label>
                    <label class="block">
                        <span class="mb-2 block text-sm font-medium text-slate-700">Deskripsi</span>
                        <textarea name="description" rows="3" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white">${escapeHtml(currentAssignment.description)}</textarea>
                    </label>
                    <div class="grid gap-5 md:grid-cols-2">
                        <label class="block">
                            <span class="mb-2 block text-sm font-medium text-slate-700">Tanggal/waktu buka</span>
                            <input type="datetime-local" name="started_at" value="${escapeHtml(currentAssignment.startedAt)}" required class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white">
                        </label>
                        <label class="block">
                            <span class="mb-2 block text-sm font-medium text-slate-700">Tanggal/waktu tutup</span>
                            <input type="datetime-local" name="ended_at" value="${escapeHtml(currentAssignment.endedAt)}" required class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white">
                        </label>
                    </div>
                `;

                const configs = {
                    submission: {
                        action: @json(route('teacher.course.assignments.store', $course->id)),
                        help: 'Buat rentang waktu tugas untuk pertemuan ini. Setiap mahasiswa di kelas ini akan mendapat data pengumpulan otomatis.',
                        submit: 'Buat Tugas',
                        fields: assignmentFields,
                    },
                    attendance: {
                        action: @json(route('teacher.course.attendances.store', $course->id)),
                        help: 'Buat satu rentang waktu presensi untuk pertemuan ini. Setiap mahasiswa di kelas ini akan mendapat data presensi otomatis.',
                        submit: 'Buat Presensi',
                        fields: attendanceFields,
                    },
                    materials: {
                        action: @json(route('teacher.course.materials.store', $course->id)),
                        help: 'Buat data materi. Pilih Dokumen untuk unggahan, atau Video/Tautan untuk URL eksternal.',
                        submit: 'Unggah Materi',
                        fields: materialFields,
                    },
                };

                const setType = () => {
                    const config = configs[typeSelect.value];
                    const isMaterialEdit = mode === 'edit' && typeSelect.value === 'materials';
                    const isAttendanceEdit = mode === 'edit' && typeSelect.value === 'attendance';
                    const isAssignmentEdit = mode === 'edit' && typeSelect.value === 'submission';
                    form.action = isMaterialEdit ? currentMaterial.action : isAttendanceEdit ? currentAttendance.action : isAssignmentEdit ? currentAssignment.action : config.action;
                    setMethod(isMaterialEdit || isAttendanceEdit || isAssignmentEdit ? 'PUT' : null);
                    submit.textContent = isMaterialEdit ? 'Perbarui Materi' : isAttendanceEdit ? 'Perbarui Presensi' : isAssignmentEdit ? 'Perbarui Tugas' : config.submit;
                    modalTitle.textContent = isMaterialEdit ? 'Ubah materi' : isAttendanceEdit ? 'Ubah presensi' : isAssignmentEdit ? 'Ubah tugas' : 'Tambah aktivitas mata kuliah';
                    fields.innerHTML = typeof config.fields === 'function' ? config.fields() : config.fields;
                    bindMaterialTypeSelect();
                };

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

                document.querySelectorAll('[data-open-activity-modal]').forEach((button) => {
                    button.addEventListener('click', () => {
                        mode = 'create';
                        currentMaterial = {};
                        currentAttendance = {};
                        currentAssignment = {};
                        form.reset();
                        typeWrapper.classList.remove('hidden');
                        typeSelect.value = 'submission';
                        meetingInput.value = button.dataset.meeting;
                        meetingLabel.textContent = button.dataset.meeting;
                        setType();
                        openModal();
                    });
                });

                document.querySelectorAll('[data-edit-assignment-modal]').forEach((button) => {
                    button.addEventListener('click', () => {
                        mode = 'edit';
                        currentMaterial = {};
                        currentAttendance = {};
                        currentAssignment = {
                            action: button.dataset.action,
                            title: button.dataset.title || '',
                            description: button.dataset.description || '',
                            startedAt: button.dataset.startedAt || '',
                            endedAt: button.dataset.endedAt || '',
                        };
                        form.reset();
                        typeWrapper.classList.add('hidden');
                        typeSelect.value = 'submission';
                        meetingInput.value = button.dataset.meeting;
                        meetingLabel.textContent = button.dataset.meeting;
                        setType();
                        openModal();
                    });
                });

                document.querySelectorAll('[data-edit-attendance-modal]').forEach((button) => {
                    button.addEventListener('click', () => {
                        mode = 'edit';
                        currentMaterial = {};
                        currentAssignment = {};
                        currentAttendance = {
                            action: button.dataset.action,
                            title: button.dataset.title || '',
                            startedAt: button.dataset.startedAt || '',
                            endedAt: button.dataset.endedAt || '',
                        };
                        form.reset();
                        typeWrapper.classList.add('hidden');
                        typeSelect.value = 'attendance';
                        meetingInput.value = button.dataset.meeting;
                        meetingLabel.textContent = button.dataset.meeting;
                        setType();
                        openModal();
                    });
                });

                document.querySelectorAll('[data-edit-material-modal]').forEach((button) => {
                    button.addEventListener('click', () => {
                        mode = 'edit';
                        currentAttendance = {};
                        currentAssignment = {};
                        currentMaterial = {
                            action: button.dataset.action,
                            title: button.dataset.title || '',
                            description: button.dataset.description || '',
                            materialType: button.dataset.materialType || 'document',
                            externalLink: button.dataset.externalLink || '',
                        };
                        form.reset();
                        typeWrapper.classList.add('hidden');
                        typeSelect.value = 'materials';
                        meetingInput.value = button.dataset.meeting;
                        meetingLabel.textContent = button.dataset.meeting;
                        setType();
                        openModal();
                    });
                });

                document.querySelectorAll('[data-close-activity-modal]').forEach((button) => {
                    button.addEventListener('click', closeModal);
                });

                typeSelect.addEventListener('change', () => {
                    currentMaterial = {};
                    currentAttendance = {};
                    currentAssignment = {};
                    setType();
                });

                setType();
            });
        </script>
    </body>
</html>
