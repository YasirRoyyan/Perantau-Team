@extends('layouts.app', [
    'title' => 'Interiology - Dashboard',
    'bodyClass' => 'dashboard-shell',
    'styles' => ['assets/css/shared.css', 'assets/css/auth.css', 'assets/css/post-modal.css'],
])

@section('body')
    @include('partials.nav')

    @php
        $displayName = trim($user->name);
        $handle = '@'.strtolower(preg_replace('/[^a-z0-9]+/i', '', $displayName ?: 'interiology'));
        $bio = $user->bio ?: 'Spread love and inspiration on interior.';
    @endphp

    <main class="dashboard-page" data-dashboard-realtime data-current-user-id="{{ $user->id }}">
        <aside class="dashboard-profile-card" aria-label="Profil pengguna">
            
            {{-- REVISI AVATAR DASHBOARD: PAKSA MATIKAN BACKGROUND IMAGE BAWAAN CSS --}}
            <a href="{{ route('user.profile', $user->name) }}" style="text-decoration: none; display: block; margin: 0 auto 15px auto; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='scale(1)'">
                {{-- Hanya tampilkan foto jika user mengunggah foto profil asli --}}
                @if ($user->avatar && head(explode('/', $user->avatar)) !== 'dummy' && \Storage::disk('public')->exists($user->avatar))
                    <div class="dashboard-avatar-wrapper" style="width: 150px; height: 150px; margin: 0 auto; flex-shrink: 0;">
                        <img src="{{ \Storage::url($user->avatar) }}" 
                             alt="Foto Profil {{ $displayName }}" 
                             style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; aspect-ratio: 1/1; display: block; border: 2px solid #ffffff;">
                    </div>
                @else
                    {{-- DEFAULT: Ditambahkan 'background-image: none !important' agar gambar dummy CSS hilang total --}}
                    <div class="dashboard-avatar" style="margin: 0 auto; width: 150px; height: 150px; border-radius: 50%; background-color: #5d534a; color: #e0dacb; display: flex; align-items: center; justify-content: center; font-size: 3.5rem; font-weight: bold; border: 2px solid #ffffff; font-family: serif; box-shadow: 0 4px 10px rgba(0,0,0,0.15); background-image: none !important;">
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
                    <dd data-dashboard-total-likes style="font-size: 1.6rem; font-weight: bold; color: #ffffff; margin: 0; order: 2;">{{ $totalLikes }}</dd>
                </div>
            </dl>

            <a href="{{ route('user.profile', $user->name) }}" class="dashboard-edit-profile dashboard-view-profile" aria-label="Buka profil Interiorgram kamu">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M20 21a8 8 0 0 0-16 0"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                Profil Saya
            </a>

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

                    {{-- MODIFIKASI: Mengarahkan tombol untuk memicu fungsi buka modal upload --}}
                    <button type="button" class="dashboard-upload" style="flex-shrink: 0;" onclick="openUploadModal()">
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

            {{-- GRID GALERI UTAMA: Sekarang membaca data dinamis asli hasil upload database --}}
            <div class="dashboard-gallery" aria-label="Galeri inspirasi interior">
                @forelse ($galleryItems as $item)
                    @include('partials.post-card', ['post' => $item, 'likedPostIds' => $likedPostIds, 'favoritedPostIds' => $favoritedPostIds])
                @empty
                    {{-- Tampilan interaktif jika database tabel posts masih kosong --}}
                    <div style="grid-column: 1/-1; text-align: center; padding: 80px 20px; color: #ccc; font-family: sans-serif;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom: 12px; opacity: 0.6;">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <polyline points="21 15 16 10 5 21"></polyline>
                        </svg>
                        <p style="font-size: 1.1rem; margin: 0;">Belum ada inspirasi interior yang dibagikan.</p>
                        <p style="font-size: 0.85rem; opacity: 0.7; margin: 5px 0 0 0;">Yuk, jadilah orang pertama yang membagikan karya desain interior ruangan!</p>
                    </div>
                @endforelse
            </div>
        </section>
    </main>

    @include('partials.post-detail-modal')

    {{-- 🌟 1. MODAL POP-UP CUSTOM: UPLOAD POSTINGAN BARU (TWO-COLUMN INTERACTIVE LAYOUT) 🌟 --}}
    <div id="upload-post-modal" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.7); backdrop-filter: blur(4px); align-items: center; justify-content: center; transition: all 0.3s ease;">
        <form action="{{ route('post.store') }}" method="POST" enctype="multipart/form-data" style="background-color: #ffffff; width: 90%; max-width: 850px; border-radius: 16px; overflow: hidden; display: flex; box-shadow: 0 20px 40px rgba(0,0,0,0.4); max-height: 90vh; transform: scale(0.95); transition: transform 0.3s ease;" id="upload-modal-box">
            @csrf
            
            {{-- SECTOR KIRI: Area Dropzone Tempat Ambil Foto Gambar & Preview Real-Time --}}
            <div style="flex: 1.2; background-color: #f1ede6; display: flex; align-items: center; justify-content: center; position: relative; border-right: 1px solid #e0dacb; min-height: 450px;">
                <input type="file" name="image" id="post-image-input" accept="image/*" required style="position: absolute; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 10;" onchange="previewPostImage(this)">
                
                {{-- State Sebelum File Foto Dipilih --}}
                <div id="upload-placeholder" style="text-align: center; padding: 20px; color: #5d534a; font-family: sans-serif;">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom: 10px;">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                    <p style="font-weight: bold; margin: 0 0 5px 0;">Pilih Gambar Interior</p>
                    <p style="font-size: 0.8rem; opacity: 0.7; margin: 0;">Klik atau seret file foto ke area ini</p>
                </div>

                {{-- State Preview Gambar Ketika Berhasil Terbaca Lokal --}}
                <img id="upload-image-preview" src="#" alt="Preview" style="display: none; width: 100%; height: 100%; object-fit: cover; position: absolute; top:0; left:0;">
            </div>

            {{-- SECTOR KANAN: Form Input Konten Caption, Identitas Akun, & Jumlah Status Post --}}
            <div style="flex: 1; padding: 25px; display: flex; flex-direction: column; justify-content: space-between; background-color: #ffffff; font-family: sans-serif;">
                <div>
                    {{-- Header Pop-up & Tombol Silang --}}
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h3 style="margin: 0; font-size: 1.2rem; color: #5d534a; font-weight: 600;">Buat Postingan Baru</h3>
                        <button type="button" onclick="closeUploadModal()" style="background: none; border: none; font-size: 1.6rem; cursor: pointer; color: #a09485; line-height: 1;">&times;</button>
                    </div>

                    {{-- Metadata Info Akun Aktif & Status Jumlah Postingan Sekarang --}}
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                        @if ($user->avatar && \Storage::disk('public')->exists($user->avatar))
                            <img src="{{ \Storage::url($user->avatar) }}" style="width: 42px; height: 42px; border-radius: 50%; object-fit: cover; border: 1px solid #e0dacb;">
                        @else
                            <div style="width: 42px; height: 42px; border-radius: 50%; background-color: #5d534a; color: #e0dacb; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.95rem;">
                                {{ strtoupper(substr($displayName, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <p style="margin: 0; font-weight: bold; color: #333; font-size: 0.95rem;">{{ $handle }}</p>
                            {{-- REQUEST REVISI: "dibawah foto dan usn profile ada status jumlah postingan" --}}
                            <p style="margin: 2px 0 0 0; font-size: 0.78rem; color: #777; font-weight: 500;">{{ $totalPosts }} postingan dibagikan</p>
                        </div>
                    </div>

                    {{-- Kolom Input Editor Teks Caption --}}
                    <div style="margin-bottom: 15px;">
                        <textarea name="caption" rows="6" placeholder="Tulis deskripsi / caption inspirasi ruangan interior kamu di sini..." style="width: 100%; border: 1px solid #e0dacb; border-radius: 8px; padding: 12px; font-size: 0.95rem; resize: none; box-sizing: border-box; outline: none; font-family: sans-serif; color: #333;" oninput="countCaptionChars(this)"></textarea>
                        <div style="text-align: right; font-size: 0.75rem; color: #a09485; margin-top: 5px;">
                            <span id="char-counter">0</span>/1000 karakter
                        </div>
                    </div>
                </div>

                {{-- Action Panel Buttons --}}
                <div style="display: flex; gap: 10px;">
                    <button type="button" onclick="closeUploadModal()" style="flex: 1; background-color: #f1ede6; color: #5d534a; border: none; padding: 12px; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 0.95rem;">Batal</button>
                    <button type="submit" style="flex: 2; background-color: #d17a22; color: #ffffff; border: none; padding: 12px; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 0.95rem; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#b8661b'" onmouseout="this.style.backgroundColor='#d17a22'">Post / Bagikan</button>
                </div>
            </div>
        </form>
    </div>

    {{-- 🌟 2. MODAL POP-UP UI CUSTOM: ALERTS PENCARIAN USER TIDAK DITEMUKAN 🌟 --}}
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

    {{-- SCRIPTS LOGIKA PENGENDALI OPERASIONAL POP-UP MODAL --}}
    <script>
        // --- LOGIKA UTAMA MODAL UPLOAD POSTINGAN BARU ---
        function openUploadModal() {
            const modal = document.getElementById('upload-post-modal');
            const modalBox = document.getElementById('upload-modal-box');
            modal.style.display = 'flex';
            setTimeout(() => {
                modalBox.style.transform = 'scale(1)';
            }, 50);
        }

        function closeUploadModal() {
            const modal = document.getElementById('upload-post-modal');
            const modalBox = document.getElementById('upload-modal-box');
            modalBox.style.transform = 'scale(0.95)';
            
            setTimeout(() => {
                modal.style.display = 'none';
                // Reset isian form jika user membatalkan upload
                document.getElementById('post-image-input').value = "";
                document.getElementById('upload-image-preview').style.display = 'none';
                document.getElementById('upload-placeholder').style.display = 'block';
                document.getElementById('char-counter').innerText = "0";
                document.querySelector('textarea[name="caption"]').value = "";
            }, 150);
        }

        // Membaca berkas gambar lokal dari PC dan memajangnya langsung di sisi kiri modal
        function previewPostImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('upload-image-preview').src = e.target.result;
                    document.getElementById('upload-image-preview').style.display = 'block';
                    document.getElementById('upload-placeholder').style.display = 'none';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Live counter penghitung batas karakter caption teks
        function countCaptionChars(textarea) {
            document.getElementById('char-counter').innerText = textarea.value.length;
        }


        // --- LOGIKA MODAL ALERT USER PENCARIAN (FITUR SEBELUMNYA) ---
        function showCustomAlert(message) {
            const modal = document.getElementById('custom-alert-modal');
            const modalBox = document.getElementById('modal-box');
            document.getElementById('modal-alert-message').innerText = message;
            
            modal.style.display = 'flex';
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
                        showCustomAlert(`Maaf, akun dengan nama "@${username}" tidak ditemukan atau belum terdaftar di dalam database Interiology.`);
                    }
                })
                .catch(error => {
                    console.error('Error fetching user:', error);
                    showCustomAlert('Terjadi gangguan koneksi jaringan, mohon coba beberapa saat lagi.');
                });
        }

        // Fitur Tambahan: Klik di luar area kotak modal putih/coklat untuk menutup modal otomatis
        window.onclick = function(event) {
            const alertModal = document.getElementById('custom-alert-modal');
            const uploadModal = document.getElementById('upload-post-modal');
            
            if (event.target == alertModal) {
                closeCustomAlert();
            }
            if (event.target == uploadModal) {
                closeUploadModal();
            }
        }
    </script>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/vendor/pusher.min.js') }}?v={{ filemtime(public_path('assets/js/vendor/pusher.min.js')) }}"></script>
    <script>
        window.InteriologyReverb = {
            key: @json(config('broadcasting.connections.reverb.key')),
            host: @json(config('broadcasting.connections.reverb.options.host')),
            port: @json((int) config('broadcasting.connections.reverb.options.port')),
            forceTLS: @json((config('broadcasting.connections.reverb.options.scheme') ?? 'https') === 'https'),
            authEndpoint: '/broadcasting/auth',
        };
    </script>
    <script src="{{ asset('assets/js/post-modal.js') }}?v={{ filemtime(public_path('assets/js/post-modal.js')) }}"></script>
    <script src="{{ asset('assets/js/reverb-client.js') }}?v={{ filemtime(public_path('assets/js/reverb-client.js')) }}"></script>
@endpush
