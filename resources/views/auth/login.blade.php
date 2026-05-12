<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Campus LMS') }} | Login</title>

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
        <div class="relative flex min-h-screen items-center justify-center overflow-hidden px-6 py-10">
            <div class="pointer-events-none absolute inset-0 -z-10 bg-[radial-gradient(circle_at_top_left,_rgba(14,165,233,0.16),_transparent_35%),radial-gradient(circle_at_bottom_right,_rgba(245,158,11,0.12),_transparent_32%),linear-gradient(to_bottom,_#f8fafc,_#eef2ff_48%,_#f8fafc)]"></div>

            <main class="grid w-full max-w-5xl gap-6 lg:grid-cols-[0.95fr_1.05fr]">
                <section class="rounded-[2rem] bg-slate-950 p-8 text-white shadow-2xl shadow-slate-900/20 lg:p-10">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-3">
                        <span class="grid h-11 w-11 place-items-center rounded-2xl bg-white text-sm font-semibold text-slate-950">LMS</span>
                        <span>
                            <span class="block text-[0.7rem] font-semibold uppercase tracking-[0.28em] text-sky-300">Campus learning</span>
                            <span class="block text-lg font-semibold">Student Portal</span>
                        </span>
                    </a>

                    <div class="mt-12 space-y-5">
                        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-sky-300">Login entry point</p>
                        <h1 class="text-4xl font-semibold tracking-tight">Your student account can connect here.</h1>
                        <p class="max-w-xl text-sm leading-6 text-slate-300">
                            This workspace does not include a full authentication flow yet, so the page is presented as a polished login entry point for the LMS main page.
                        </p>
                    </div>

                    <div class="mt-10 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                            <p class="text-sm text-slate-400">What comes next</p>
                            <p class="mt-2 text-lg font-semibold">Email + password sign in</p>
                        </div>
                        <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                            <p class="text-sm text-slate-400">Need help</p>
                            <p class="mt-2 text-lg font-semibold">Use support below</p>
                        </div>
                    </div>
                </section>

                <section class="rounded-[2rem] border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/70 lg:p-10">
                    <div class="max-w-md">
                        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-sky-700">Student login</p>
                        <h2 class="mt-3 text-3xl font-semibold tracking-tight text-slate-950">Sign in to access classes, announcements, and assignments.</h2>
                        <p class="mt-4 text-sm leading-6 text-slate-600">
                            Use this page as the handoff from the main portal. If you want, the login form can be connected to Laravel authentication later.
                        </p>
                    </div>

                    <form class="mt-8 space-y-5">
                        <label class="block">
                            <span class="mb-2 block text-sm font-medium text-slate-700">Email address</span>
                            <input type="email" name="email" placeholder="student@campus.ac.id" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:bg-white" />
                        </label>

                        <label class="block">
                            <span class="mb-2 block text-sm font-medium text-slate-700">Password</span>
                            <input type="password" name="password" placeholder="Your password" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:bg-white" />
                        </label>

                        <div class="flex items-center justify-between text-sm text-slate-600">
                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500" />
                                Remember me
                            </label>
                            <a href="mailto:support@campuslms.ac.id" class="font-medium text-sky-700 transition hover:text-sky-600">Forgot access?</a>
                        </div>

                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/20 transition hover:-translate-y-0.5 hover:bg-slate-800">
                            Continue
                        </button>
                    </form>

                    <div class="mt-6 rounded-3xl bg-slate-50 p-5 ring-1 ring-slate-200">
                        <p class="text-sm font-medium text-slate-900">Need immediate help?</p>
                        <p class="mt-2 text-sm leading-6 text-slate-600">
                            Email support@campuslms.ac.id or call +62 812 3456 7890 for account help.
                        </p>
                    </div>
                </section>
            </main>
        </div>
    </body>
</html>
