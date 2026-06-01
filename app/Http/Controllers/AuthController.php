<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginValue = trim($credentials['login']);
        $remember = $request->boolean('remember');

        if (! Auth::attempt(['username' => $loginValue, 'password' => $credentials['password']], $remember)) {
            throw ValidationException::withMessages([
                'login' => 'The provided credentials do not match our records.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->route($this->dashboardRoute($request->user()));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function dashboardRoute($user): string
    {
        return match ($user->role) {
            'admin' => 'admin.dashboard',
            'teacher' => 'teacher.dashboard',
            default => 'dashboard',
        };
    }
}
