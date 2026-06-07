<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'login' => ['required_without:email', 'nullable', 'string'],
            'email' => ['nullable', 'email'],
            'password' => ['required', 'string'],
        ]);

        $identifier = $validated['login'] ?? $validated['email'] ?? '';
        $field = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        if (! Auth::attempt([$field => $identifier, 'password' => $validated['password']], $request->boolean('remember'))) {
            return back()
                ->withErrors(['login' => 'Nama pengguna atau password tidak sesuai.'])
                ->onlyInput('login');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $validated['role'] = 'user';

        User::create($validated);

        return redirect()
            ->route('login')
            ->with('status', 'Pendaftaran berhasil. Silakan login dengan email dan password yang baru didaftarkan.')
            ->with('registered_email', $validated['email']);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
