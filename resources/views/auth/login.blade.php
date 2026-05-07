@extends('layouts.app', [
    'title' => 'Interiology - Masuk',
    'styles' => ['assets/css/shared.css', 'assets/css/auth.css'],
    'bodyClass' => 'auth-body',
])

@section('body')
    <main class="auth-page auth-page--login">
        <section class="auth-form-panel">
            <div class="auth-header">
                <a href="{{ route('home') }}" class="auth-back" aria-label="Kembali ke beranda">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M15 18 9 12l6-6"></path>
                        <path d="M10 12h9"></path>
                    </svg>
                </a>
                <a href="{{ route('home') }}" class="auth-brand">Interiology</a>
            </div>

            <div class="auth-form-wrap">
                <h1 class="auth-title">Selamat datang kembali!</h1>
                <p class="auth-copy">Masukkan nama pengguna dan sandi akun anda</p>

                @include('partials.alerts')

                <form action="{{ route('login.store') }}" method="POST" class="auth-form" autocomplete="off">
                    @csrf

                    <input type="text" name="login" value="" placeholder="Nama Pengguna" aria-label="Nama Pengguna" autocomplete="off" required autofocus>
                    <input type="password" name="password" value="" placeholder="Kata Sandi" aria-label="Kata Sandi" autocomplete="new-password" required>

                    <button type="submit" class="btn-submit">Masuk</button>
                </form>

                <p class="auth-switch">Belum punya akun? <a href="{{ route('register') }}">Daftar disini</a></p>
            </div>
        </section>

        <section class="auth-visual" aria-hidden="true">
            <p>Kamu bisa<br>mendapatkan<br>selera ruanganmu<br>di Interiology</p>
        </section>
    </main>
@endsection
