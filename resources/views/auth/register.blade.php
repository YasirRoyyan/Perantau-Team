@extends('layouts.app', [
    'title' => 'Interiology - Daftar',
    'styles' => ['assets/css/shared.css', 'assets/css/auth.css'],
    'bodyClass' => 'auth-body',
])

@section('body')
    <main class="auth-page auth-page--register">
        <section class="auth-form-panel">
            <div class="auth-header">
                <a href="{{ route('home') }}" class="auth-back" aria-label="Kembali ke beranda">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M19 12H6"></path>
                        <path d="m12 18-6-6 6-6"></path>
                    </svg>
                </a>
                <a href="{{ route('home') }}" class="auth-brand">Interiology</a>
            </div>

            <div class="auth-form-wrap">
                <h1 class="auth-title">Mulai buat akunmu</h1>
                <p class="auth-copy">Bebas akses semua fitur setelah punya akun</p>

                @include('partials.alerts')

                <form action="{{ route('register.store') }}" method="POST" class="auth-form" autocomplete="off">
                    @csrf

                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" aria-label="Email" autocomplete="off" required>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama Pengguna" aria-label="Nama Pengguna" autocomplete="off" required>
                    <input type="password" name="password" placeholder="Kata Sandi" aria-label="Kata Sandi" autocomplete="new-password" required>
                    <input type="password" name="password_confirmation" placeholder="Ulangi Kata Sandi" aria-label="Ulangi Kata Sandi" autocomplete="new-password" required>

                    <button type="submit" class="btn-submit">Daftar</button>
                </form>

                <p class="auth-switch">Sudah punya akun? <a href="{{ route('login') }}">Login disini</a></p>
            </div>
        </section>

        <section class="auth-visual" aria-hidden="true">
            <p>Kamu bisa<br>mendapatkan<br>selera ruanganmu<br>di Interiology</p>
        </section>
    </main>
@endsection
