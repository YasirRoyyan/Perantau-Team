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
                    <img id="avatarPreview" src="{{ $user->avatar && \Storage::disk('public')->exists($user->avatar) ? \Storage::url($user->avatar) : asset('assets/images/default-avatar.png') }}"  
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
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" maxlength="25" pattern="[A-Za-z0-9]+" data-alpha-num-only required>
                    <small class="form-note">Maksimal 25 karakter, hanya huruf dan angka tanpa spasi.</small>
                </label>

                <label>
                    Email
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                </label>

                <label>
                    Nomor Telepon
                    <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: +628123456789" maxlength="20" inputmode="tel" pattern="\+?[0-9]*" data-phone-only>
                    <small class="form-note">Hanya angka, boleh diawali kode negara seperti +62.</small>
                </label>

                <label>
                    Kota
                    <input type="text" name="city" value="{{ old('city', $user->city) }}" placeholder="Contoh: Bandung" maxlength="80" pattern="[A-Za-z\s]+" list="cityOptions" data-city-input autocomplete="off">
                    <datalist id="cityOptions"></datalist>
                    <small class="form-note">Ketik huruf awal kota. Hanya huruf dan spasi.</small>
                </label>

                <label class="full-span">
                    Bio
                    <textarea name="bio" rows="4" maxlength="50" placeholder="Ceritakan selera interior kamu secara singkat" data-character-count="bioCounter">{{ old('bio', $user->bio) }}</textarea>
                    <small class="form-note"><span id="bioCounter">0</span>/50 karakter</small>
                </label>

                <label>
                    Password Baru
                    <span class="password-field">
                        <input type="password" name="password" placeholder="Kosongkan jika tidak diubah" minlength="8" maxlength="25">
                        <button type="button" class="password-toggle" aria-label="Tampilkan password" aria-pressed="false">
                            <svg class="password-eye-icon" viewBox="0 0 24 24" aria-hidden="true">
                                <path class="eye-outline" d="M2.5 12s3.45-5.5 9.5-5.5 9.5 5.5 9.5 5.5-3.45 5.5-9.5 5.5S2.5 12 2.5 12Z"></path>
                                <circle class="eye-pupil" cx="12" cy="12" r="2.65"></circle>
                                <path class="eye-slash" d="M4.75 4.75 19.25 19.25"></path>
                            </svg>
                        </button>
                    </span>
                    <small class="form-note">Minimal 8 karakter dan maksimal 25 karakter.</small>
                </label>

                <label>
                    Konfirmasi Password Baru
                    <span class="password-field">
                        <input type="password" name="password_confirmation" placeholder="Ulangi password baru" minlength="8" maxlength="25">
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

        const cityNames = [
            'Ambon', 'Balikpapan', 'Banda Aceh', 'Bandar Lampung', 'Bandung', 'Banjar',
            'Banjarbaru', 'Banjarmasin', 'Batam', 'Batu', 'Bekasi', 'Bengkulu', 'Binjai',
            'Bitung', 'Blitar', 'Bogor', 'Bontang', 'Bukittinggi', 'Cilegon', 'Cimahi',
            'Cirebon', 'Denpasar', 'Depok', 'Dumai', 'Gorontalo', 'Jakarta', 'Jambi',
            'Jayapura', 'Kediri', 'Kendari', 'Kupang', 'Langsa', 'Lhokseumawe', 'Madiun',
            'Magelang', 'Makassar', 'Malang', 'Manado', 'Mataram', 'Medan', 'Metro',
            'Mojokerto', 'Padang', 'Padang Panjang', 'Padangsidimpuan', 'Palangkaraya',
            'Palembang', 'Palopo', 'Palu', 'Pangkalpinang', 'Parepare', 'Pariaman',
            'Pasuruan', 'Payakumbuh', 'Pekalongan', 'Pekanbaru', 'Pematangsiantar',
            'Pontianak', 'Prabumulih', 'Probolinggo', 'Sabang', 'Salatiga', 'Samarinda',
            'Semarang', 'Serang', 'Sibolga', 'Singkawang', 'Solok', 'Sorong', 'Subulussalam',
            'Sukabumi', 'Surabaya', 'Surakarta', 'Tangerang', 'Tangerang Selatan',
            'Tanjungbalai', 'Tanjungpinang', 'Tarakan', 'Tasikmalaya', 'Tebing Tinggi',
            'Tegal', 'Ternate', 'Tidore Kepulauan', 'Tomohon', 'Tual', 'Yogyakarta'
        ];

        const cityInput = document.querySelector('[data-city-input]');
        const cityOptions = document.getElementById('cityOptions');

        function renderCityOptions(query = '') {
            if (!cityOptions) return;

            const normalizedQuery = query.trim().toLowerCase();
            const matches = cityNames
                .filter((city) => !normalizedQuery || city.toLowerCase().startsWith(normalizedQuery))
                .slice(0, 12);

            cityOptions.innerHTML = matches.map((city) => `<option value="${city}"></option>`).join('');
        }

        document.querySelectorAll('[data-alpha-num-only]').forEach((input) => {
            input.addEventListener('input', () => {
                input.value = input.value.replace(/[^A-Za-z0-9]/g, '');
            });
        });

        document.querySelectorAll('[data-phone-only]').forEach((input) => {
            input.addEventListener('input', () => {
                let value = input.value.replace(/[^\d+]/g, '');
                value = value.replace(/(?!^)\+/g, '');
                input.value = value;
            });
        });

        if (cityInput) {
            cityInput.addEventListener('input', () => {
                cityInput.value = cityInput.value.replace(/[^A-Za-z\s]/g, '');
                renderCityOptions(cityInput.value);
            });
            renderCityOptions(cityInput.value);
        }

        document.querySelectorAll('[data-character-count]').forEach((field) => {
            const counter = document.getElementById(field.dataset.characterCount);
            if (!counter) return;

            const updateCounter = () => {
                counter.textContent = field.value.length;
            };

            field.addEventListener('input', updateCounter);
            updateCounter();
        });
    </script>
@endpush
