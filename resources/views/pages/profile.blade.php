@extends('layouts.app', [
    'title' => 'Interiology - Profil',
    'styles' => ['assets/css/shared.css', 'assets/css/auth.css'],
])

@section('body')
    @include('partials.nav')

    <main class="profile-page">
        <section class="profile-card">
            <div class="profile-heading">
                <div>
                    <p class="eyebrow">Profil</p>
                    <h1>{{ $user->name }}</h1>
                    <p class="auth-copy">Kelola data akun dan profil singkat kamu.</p>
                </div>
                <span class="role-badge">{{ ucfirst($user->role) }}</span>
            </div>

            @include('partials.alerts')

            <form action="{{ route('profile.update') }}" method="POST" class="profile-form">
                @csrf
                @method('PUT')

                <label>
                    Nama
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                </label>

                <label>
                    Email
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                </label>

                <label>
                    Nomor Telepon
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Opsional">
                </label>

                <label>
                    Kota
                    <input type="text" name="city" value="{{ old('city', $user->city) }}" placeholder="Contoh: Bandung">
                </label>

                <label class="full-span">
                    Bio
                    <textarea name="bio" rows="4" placeholder="Ceritakan selera interior kamu secara singkat">{{ old('bio', $user->bio) }}</textarea>
                </label>

                <label>
                    Password Baru
                    <span class="password-field">
                        <input type="password" name="password" placeholder="Kosongkan jika tidak diubah">
                        <button type="button" class="password-toggle" aria-label="Tampilkan password" aria-pressed="false">
                            <svg class="password-eye-icon" viewBox="0 0 24 24" aria-hidden="true">
                                <path class="eye-outline" d="M2.5 12s3.45-5.5 9.5-5.5 9.5 5.5 9.5 5.5-3.45 5.5-9.5 5.5S2.5 12 2.5 12Z"></path>
                                <circle class="eye-pupil" cx="12" cy="12" r="2.65"></circle>
                                <path class="eye-slash" d="M4.75 4.75 19.25 19.25"></path>
                            </svg>
                        </button>
                    </span>
                </label>

                <label>
                    Konfirmasi Password Baru
                    <span class="password-field">
                        <input type="password" name="password_confirmation" placeholder="Ulangi password baru">
                        <button type="button" class="password-toggle" aria-label="Tampilkan password" aria-pressed="false">
                            <svg class="password-eye-icon" viewBox="0 0 24 24" aria-hidden="true">
                                <path class="eye-outline" d="M2.5 12s3.45-5.5 9.5-5.5 9.5 5.5 9.5 5.5-3.45 5.5-9.5 5.5S2.5 12 2.5 12Z"></path>
                                <circle class="eye-pupil" cx="12" cy="12" r="2.65"></circle>
                                <path class="eye-slash" d="M4.75 4.75 19.25 19.25"></path>
                            </svg>
                        </button>
                    </span>
                </label>

                <button type="submit" class="btn-submit full-span">Simpan Profil</button>
            </form>
        </section>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/password-toggle.js') }}"></script>
@endpush
