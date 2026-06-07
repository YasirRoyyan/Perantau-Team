@extends('layouts.app', [
    'title' => 'Interiology - Dashboard',
    'bodyClass' => 'dashboard-shell',
    'styles' => ['assets/css/shared.css', 'assets/css/auth.css'],
])

@section('body')
    @include('partials.nav')

    @php
        $displayName = trim($user->name);
        $handle = '@'.strtolower(preg_replace('/[^a-z0-9]+/i', '', $displayName ?: 'interiology'));
        $bio = $user->bio ?: 'Spread love and inspiration on interior.';
        $galleryItems = range(1, 52);

        // Tambahan variabel dummy statistik untuk halaman dashboard (sesuai request posts dan likes)
        $totalPosts = 0; 
        $totalLikes = 0;
    @endphp

    <main class="dashboard-page">
        <aside class="dashboard-profile-card" aria-label="Profil pengguna">
            
            {{-- REVISI AVATAR DASHBOARD: PAKSA MATIKAN BACKGROUND IMAGE BAWAAN CSS --}}
            <a href="{{ route('user.profile', $user->name) }}" style="text-decoration: none; display: block; margin: 0 auto 15px auto; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='scale(1)'">
                {{-- Hanya tampilkan foto jika user mengunggah foto profil asli --}}
                @if ($user->avatar && head(explode('/', $user->avatar)) !== 'dummy' && \Storage::disk('public')->exists($user->avatar))
                    <div class="dashboard-avatar-wrapper" style="width: 150px; height: 150px; margin: 0 auto; flex-shrink: 0;">
                        <img src="{{ asset('storage/' . $user->avatar) }}" 
                             alt="Foto Profil {{ $displayName }}" 
                             style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; aspect-ratio: 1/1; display: block; border: 2px solid #d17a22;">
                    </div>
                @else
                    {{-- DEFAULT: Ditambahkan 'background-image: none !important' agar gambar dummy CSS hilang total --}}
                    <div class="dashboard-avatar" style="margin: 0 auto; width: 150px; height: 150px; border-radius: 50%; background-color: #5d534a; color: #e0dacb; display: flex; align-items: center; justify-content: center; font-size: 3.5rem; font-weight: bold; border: 2px solid #d17a22; font-family: serif; box-shadow: 0 4px 10px rgba(0,0,0,0.15); background-image: none !important;">
                        {{ strtoupper(substr($displayName, 0, 1)) }}
                    </div>
                @endif
            </a>

            <h1>
                <a href="{{ route('user.profile', $user->name) }}" style="color: inherit; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#d17a22'" onmouseout="this.style.color='inherit'">
                    {{ $handle }}
                </a>
            </h1>
            
            <p>{{ $bio }}</p>

            <dl class="dashboard-social-stats" style="display: flex; justify-content: center; align-items: center; gap: 40px; margin: 20px 0; padding: 0; list-style: none;">
                <div style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                    <dt style="font-size: 0.85rem; color: #ccc; order: 1; margin-bottom: 4px;">Postingan</dt>
                    <dd style="font-size: 1.6rem; font-weight: bold; color: #ffffff; margin: 0; order: 2;">{{ $totalPosts }}</dd>
                </div>
                <div style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                    <dt style="font-size: 0.85rem; color: #ccc; order: 1; margin-bottom: 4px;">Total Suka</dt>
                    <dd style="font-size: 1.6rem; font-weight: bold; color: #ffffff; margin: 0; order: 2;">{{ $totalLikes }}</dd>
                </div>
            </dl>

            <a href="{{ route('profile.show') }}" class="dashboard-edit-profile">Edit Profile</a>

            @if ($user->role === 'admin')
                <a href="{{ route('admin.content.index') }}" class="dashboard-admin-link">Kelola Konten</a>
                <span class="sr-only">Akun Terbaru</span>
            @endif
        </aside>

        <section class="dashboard-content" aria-labelledby="dashboard-title">
            <header class="dashboard-cover">
                <div class="dashboard-cover-top" style="display: flex; align-items: center; justify-content: space-between; gap: 20px; width: 100%;">
                    <span class="dashboard-brand" style="flex-shrink: 0;">Interiorgram</span>
                    
                    {{-- FORM SEARCH --}}
                    <form id="search-user-form" onsubmit="handleUserSearch(event)" style="margin: 0; padding: 0; flex-grow: 1; max-width: 450px; display: min-content;">
                        <label class="dashboard-search" style="position: relative; display: block; width: 100%; margin: 0;">
                            <span class="sr-only">Cari username pengguna</span>
                            <input type="search" id="search-username-input" placeholder="Cari username" autocomplete="off" style="width: 100%; box-sizing: border-box;">
                        </label>
                    </form>

                    <button type="button" class="dashboard-upload" style="flex-shrink: 0;">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 15V4"></path>
                            <path d="m7 9 5-5 5 5"></path>
                            <path d="M5 12v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6"></path>
                        </svg>
                        Upload
                    </button>
                </div>
                <h2 id="dashboard-title">Jelajahi Inspirasi Interior</h2>
            </header>

            <div class="dashboard-gallery" aria-label="Galeri inspirasi interior">
                @foreach ($galleryItems as $item)
                    <article class="dashboard-gallery-card" aria-label="Inspirasi interior {{ $item }}"></article>
                @endforeach
            </div>
        </section>
    </main>

    {{-- REVISI TAMBAHAN: MODAL POP-UP UI CUSTOM (DIPAKSA HIDDEN SECARA DEFAULT) --}}
    <div id="custom-alert-modal" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.6); align-items: center; justify-content: center; backdrop-filter: blur(3px); transition: all 0.3s ease;">
        <div style="background-color: #5d534a; border: 2px solid #d17a22; border-radius: 12px; width: 90%; max-width: 400px; padding: 25px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.5); transform: scale(0.9); transition: transform 0.3s ease; font-family: sans-serif;" id="modal-box">
            <div style="background-color: rgba(209, 122, 34, 0.15); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px auto;">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#d17a22" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            </div>
            <h3 style="color: #ffffff; margin: 0 0 10px 0; font-size: 1.3rem; font-weight: 600;">Pengguna Tidak Ditemukan</h3>
            <p id="modal-alert-message" style="color: #e0dacb; font-size: 0.95rem; line-height: 1.5; margin: 0 0 20px 0;">Nama pengguna tidak terdaftar di database kami.</p>
            <button type="button" onclick="closeCustomAlert()" style="background-color: #d17a22; color: #ffffff; border: none; padding: 10px 24px; font-size: 0.95rem; font-weight: bold; border-radius: 6px; cursor: pointer; width: 100%; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#b8661b'" onmouseout="this.style.backgroundColor='#d17a22'">
                Oke, Mengerti
            </button>
        </div>
    </div>

    {{-- JAVASCRIPT LOGIKA PENCARIAN & POP-UP INTERAKTIF --}}
    <script>
        function showCustomAlert(message) {
            const modal = document.getElementById('custom-alert-modal');
            const modalBox = document.getElementById('modal-box');
            document.getElementById('modal-alert-message').innerText = message;
            
            // Tampilkan backdrop modal menggunakan flexbox alignment
            modal.style.display = 'flex';
            // Beri animasi pop-in kecil
            setTimeout(() => {
                modalBox.style.transform = 'scale(1)';
            }, 50);
        }

        function closeCustomAlert() {
            const modal = document.getElementById('custom-alert-modal');
            const modalBox = document.getElementById('modal-box');
            modalBox.style.transform = 'scale(0.9)';
            
            setTimeout(() => {
                modal.style.display = 'none';
            }, 150);
        }

        function handleUserSearch(event) {
            event.preventDefault(); 
            
            const inputElement = document.getElementById('search-username-input');
            let username = inputElement.value.trim();

            if (username.startsWith('@')) {
                username = username.substring(1);
            }

            if (username === '') {
                showCustomAlert('Silakan masukkan nama username terlebih dahulu!');
                return;
            }

            const targetUrl = `{{ url('/user') }}/${username}`;

            fetch(targetUrl)
                .then(response => {
                    if (response.status === 200) {
                        window.location.href = targetUrl;
                    } else {
                        // KINI MENGGUNAKAN POP-UP UI PREMIUM ASLI, BUKAN ALERT BROWSER LAGI
                        showCustomAlert(`Maaf, akun dengan nama "@${username}" tidak ditemukan atau belum terdaftar di dalam database Interiology.`);
                    }
                })
                .catch(error => {
                    console.error('Error fetching user:', error);
                    showCustomAlert('Terjadi gangguan koneksi jaringan, mohon coba beberapa saat lagi.');
                });
        }

        // Fitur Tambahan: Menutup modal pop-up ketika user klik di luar area kotak alert
        window.onclick = function(event) {
            const modal = document.getElementById('custom-alert-modal');
            if (event.target == modal) {
                closeCustomAlert();
            }
        }
    </script>
@endsection