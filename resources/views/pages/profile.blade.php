@extends('layouts.app', [
    'title' => 'Interiology - Profil',
    'styles' => ['assets/css/shared.css', 'assets/css/auth.css'],
])

@section('body')
    @include('partials.nav')

    <main class="profile-page">

    

        <section class="profile-card">
            
        <a href="{{ route('dashboard') }}" class="back-button" aria-label="Kembali ke beranda">
            <span class="back-icon" aria-hidden="true"></span>
        </a>
        <br>
        <br>
            <div class="profile-heading" style="display: flex; align-items: center; gap: 20px; width: 100%; margin-bottom: 25px;">
                <br>
                <div class="profile-avatar-wrapper" style="flex-shrink: 0; width: 80px; height: 80px;">
                    <img id="avatarPreview" src="{{ $user->avatar && \Storage::disk('public')->exists($user->avatar) ? asset('storage/' . $user->avatar) : asset('assets/images/default-avatar.png') }}"  
                         alt="Foto Profil"
                         style="width: 100%; height: 100%; border-radius: 50%; aspect-ratio: 1/1; object-fit: cover; border: 2px solid #d17a22; display: block;">
                </div>

                <div style="flex-grow: 1;">
                    <p class="eyebrow" style="margin: 0; color: #d17a22; font-weight: bold; font-size: 0.85rem; text-transform: uppercase;">Profil</p>
                    <h1 style="margin: 5px 0 0 0; font-size: 2.2rem; font-family: serif;">{{ $user->name }}</h1>
                    <p class="auth-copy" style="margin: 2px 0 0 0; color: #666; font-size: 0.9rem;">Kelola data akun dan profil singkat kamu.</p>
                </div>

                <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 10px; flex-shrink: 0;">
                    <span class="role-badge" style="margin: 0;">{{ ucfirst($user->role) }}</span>
                    
                    <label style="background-color: #d17a22; color: white; padding: 6px 14px; border-radius: 4px; cursor: pointer; font-size: 0.85rem; font-weight: 500; text-align: center; transition: background 0.2s;">
                         Ganti Foto
                        <input type="file" id="avatarInput" name="avatar" form="profileForm" accept="image/*" style="display: none;" onchange="previewImage(event)">
                    </label>
                </div>
            </div>

            @include('partials.alerts')

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm" class="profile-form">
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
    
    <script>
        function previewImage(event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const preview = document.getElementById('avatarPreview');
                    if (preview) {
                        preview.src = e.target.result;
                    }
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
