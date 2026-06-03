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
        <x-sweet-alert-messages />
        <div class="relative flex min-h-screen items-center justify-center overflow-hidden px-6 py-10">
            <div class="pointer-events-none absolute inset-0 -z-10 bg-[radial-gradient(circle_at_top_left,_rgba(14,165,233,0.16),_transparent_35%),radial-gradient(circle_at_bottom_right,_rgba(245,158,11,0.12),_transparent_32%),linear-gradient(to_bottom,_#f8fafc,_#eef2ff_48%,_#f8fafc)]"></div>

                <section class="rounded-[2rem] border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/70 lg:p-10">
                    <div class="max-w-md w-full items-center justify-center">
                        <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-950 text-center w-full">Sign In</h2>
                    </div>

                    <form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-5">
                        @csrf

                        @if ($errors->any())
                            <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                                <p class="font-semibold">{{ __('auth.login.error_title') }}</p>
                                <p class="mt-1">{{ __('auth.login.error_text') }}</p>
                            </div>
                        @endif

                        <label class="block">
                            <span class="mb-2 block text-sm font-medium text-slate-700">Username</span>
                            <input type="text" name="login" value="{{ old('login') }}" placeholder="Your username" required autofocus class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:bg-white" />
                            @error('login')
                                <span class="mt-2 block text-xs text-rose-600">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="block">
                            <span class="mb-2 block text-sm font-medium text-slate-700">Password</span>
                            <input type="password" name="password" required placeholder="Your password" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:bg-white" />
                            @error('password')
                                <span class="mt-2 block text-xs text-rose-600">{{ $message }}</span>
                            @enderror
                        </label>

                        <div class="flex items-center justify-between text-sm text-slate-600">
                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" name="remember" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500" />
                                Remember me
                            </label>
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
