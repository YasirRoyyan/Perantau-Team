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
                        <path d="M19 12H6"></path>
                        <path d="m12 18-6-6 6-6"></path>
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

                    <label class="auth-field">
                        <span class="sr-only">Nama Pengguna</span>
                        <input type="text" name="login" value="{{ old('login') }}" placeholder="Nama Pengguna" aria-label="Nama Pengguna" autocomplete="off" maxlength="25" pattern="\S+" data-no-space="true" data-limit-message="Nama pengguna sudah mencapai batas 25 karakter." data-space-message="Nama pengguna tidak boleh menggunakan spasi." required autofocus>
                        <small class="auth-limit-note" aria-live="polite"></small>
                    </label>

                    <label class="auth-field">
                        <span class="sr-only">Kata Sandi</span>
                        <input type="password" name="password" value="" placeholder="Kata Sandi" aria-label="Kata Sandi" autocomplete="new-password" maxlength="25" data-limit-message="Kata sandi sudah mencapai batas 25 karakter." required>
                        <small class="auth-limit-note" aria-live="polite"></small>
                    </label>

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

@push('scripts')
    <script>
        document.querySelectorAll('.auth-field input').forEach((input) => {
            const note = input.closest('.auth-field')?.querySelector('.auth-limit-note');
            if (!note) return;

            const updateNote = () => {
                const hasSpace = input.dataset.noSpace === 'true' && /\s/.test(input.value);
                const atLimit = input.maxLength > 0 && input.value.length >= input.maxLength;

                note.textContent = hasSpace ? input.dataset.spaceMessage : (atLimit ? input.dataset.limitMessage : '');
                note.classList.toggle('is-visible', hasSpace || atLimit);
            };

            input.addEventListener('input', updateNote);
            updateNote();
        });
    </script>
@endpush
