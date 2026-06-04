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
            $courseCode = $course->classes?->class_code ?? $class?->class_code ?? 'COURSE';
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

                <section class="space-y-10">
                    @foreach ($meetings as $meeting)
                        <article class="space-y-5">
                            <div class="flex items-center gap-4">
                                <div class="h-px flex-1 bg-gradient-to-r from-transparent via-slate-300 to-slate-300"></div>
                                <h2 class="text-center text-xl font-semibold tracking-tight text-slate-950">{{ $meeting['title'] }}</h2>
                                <div class="h-px flex-1 bg-gradient-to-l from-transparent via-slate-300 to-slate-300"></div>
                            </div>

                            <div class="grid gap-4">
                                @foreach ($meeting['items'] as $item)
                                    <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm">
                                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                            <div>
                                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ $item['type'] }}</p>
                                                <a href="#" class="mt-2 inline-flex text-lg font-semibold text-slate-950 transition hover:text-sky-700">{{ $item['title'] }}</a>
                                            </div>

                                            @if ($item['done'])
                                                <span class="inline-flex w-fit items-center rounded-full bg-emerald-100 px-4 py-2 text-sm font-semibold text-emerald-700">✓ Done</span>
                                            @else
                                                <button type="button" class="inline-flex w-fit items-center rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-sky-300 hover:bg-sky-50 hover:text-sky-700">Mark as Done</button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach

                                @foreach ($meeting['materials'] as $material)
                                    <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm">
                                        <div class="space-y-4">
                                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                                <div>
                                                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Materials · {{ ucfirst($material->material_type) }}</p>
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

                                                <button type="button" class="inline-flex w-fit items-center rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-sky-300 hover:bg-sky-50 hover:text-sky-700">Mark as Done</button>
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
    </body>
</html>
