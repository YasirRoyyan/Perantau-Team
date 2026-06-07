@extends('layouts.app', [
    'title' => 'Interiology - ' . $user->name,
    'styles' => ['assets/css/shared.css', 'assets/css/auth.css'],
])

@section('body')
    {{-- Navbar disamakan dengan halaman lain --}}
    @include('partials.nav')

    @php
        $displayName = trim($user->name);
        $handle = '@'.strtolower(preg_replace('/[^a-z0-9]+/i', '', $displayName ?: 'interiology'));
        $bio = $user->bio ?: 'Spread love and inspiration on interior.';
        
        $totalPosts = 0; 
        $totalLikes = 0; 
    @endphp

    {{-- Bagian Atas / Header Profil (Warna Cokelat Gelap) --}}
    <header class="profile-feed-top-section" style="background-color: #5d534a; color: #ffffff; padding: 50px 20px; text-align: center;">
        <div style="width: 100%; max-width: 935px; margin: 0 auto; position: relative;">
            
            {{-- Tombol Back Arrow di kiri atas --}}
            <div style="position: absolute; left: 20px; top: -10px;">
                <a href="{{ route('dashboard') }}" style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%; border: 1px solid rgba(255,255,255,0.4); background-color: transparent; color: #ffffff; transition: all 0.2s ease; text-decoration: none;" onmouseover="this.style.borderColor='#ffffff'; this.style.backgroundColor='rgba(255,255,255,0.1)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.4)'; this.style.backgroundColor='transparent'">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                </a>
            </div>

            {{-- Flex Container Info Utama --}}
            <div style="display: flex; align-items: center; justify-content: center; gap: 60px; max-width: 700px; margin: 0 auto; text-align: left;">
                
                <!-- Avatar Lingkaran Besar -->
                <div class="profile-feed-avatar" style="width: 150px; height: 150px; flex-shrink: 0;">
                    @if ($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar {{ $displayName }}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; aspect-ratio: 1/1; border: 3px solid #ffffff;">
                    @else
                        <div style="width: 100%; height: 100%; border-radius: 50%; background-color: #e0dacb; color: #5d534a; display: flex; align-items: center; justify-content: center; font-size: 3.5rem; font-weight: bold; font-family: serif;">
                            {{ strtoupper(substr($displayName, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <!-- Data & Tombol Aksi -->
                <div style="flex-grow: 1;">
                    <h1 style="font-size: 1.8rem; font-family: serif; margin: 0 0 10px 0; color: #ffffff;">{{ $handle }}</h1>
                    
                    <!-- STATISTIK REVISI: Hanya Postingan dan Likes -->
                    <div style="display: flex; gap: 30px; margin-bottom: 15px; font-size: 0.9rem; opacity: 0.9;">
                        <div><strong style="font-size: 1.1rem;">{{ $totalPosts }}</strong> Postingan</div>
                        <div><strong style="font-size: 1.1rem;">{{ $totalLikes }}</strong> Total Suka</div>
                    </div>

                    <!-- Keterangan Bio -->
                    <p style="margin: 0 0 20px 0; font-size: 0.9rem; opacity: 0.85; line-height: 1.4; max-width: 350px;">{{ $bio }}</p>

                    <!-- Tombol Edit Profil -->
                    @if(auth()->id() === $user->id)
                        <a href="{{ route('profile.show') }}" style="background-color: #e0dacb; color: #333333; text-decoration: none; padding: 8px 24px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; display: inline-block; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#d4cbba'" onmouseout="this.style.backgroundColor='#e0dacb'">Edit Profil</a>
                    @endif
                </div>

            </div>
        </div>
    </header>

    {{-- Bagian Bawah / Tab Navigasi & Tampilan Konten --}}
    <main style="background-color: #fcfaf2; min-height: 50vh; padding-bottom: 60px;">
        
        {{-- Pembatas Tab --}}
        <div style="width: 100%; max-width: 935px; margin: 0 auto; border-top: 1px solid #dcd7ca; display: flex; justify-content: center; gap: 60px;">
            <!-- Tab Postingan -->
            <button id="tab-posts" onclick="switchTab('posts')" style="background: none; border: none; border-top: 2px solid #111111; color: #111111; padding: 12px 0; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                Postingan
            </button>
            
            {{-- Menu Tersimpan Hanya untuk Pemilik Akun --}}
            @if(auth()->id() === $user->id)
                <!-- Tab Tersimpan -->
                <button id="tab-saved" onclick="switchTab('saved')" style="background: none; border: none; border-top: 2px solid transparent; color: #8e8e8e; padding: 12px 0; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m19 21-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path></svg>
                    Tersimpan
                </button>
            @endif
        </div>

        {{-- AREA KONTEN CONTAINER --}}
        <div style="width: 100%; max-width: 935px; margin: 0 auto;">
            
            <div id="content-posts" class="empty-feed-container" style="text-align: center; padding: 80px 20px; color: #726255; display: block;">
                <div style="width: 80px; height: 80px; border-radius: 50%; border: 2px solid #726255; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto; opacity: 0.7;">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"></path>
                        <circle cx="12" cy="13" r="3"></circle>
                    </svg>
                </div>
                <h2 style="font-family: serif; font-size: 1.5rem; margin: 0 0 8px 0; color: #4e443c;">Belum Ada Postingan</h2>
                <p style="margin: 0; font-size: 0.9rem; opacity: 0.8; max-width: 300px; margin: 0 auto;">Inspirasi interior yang kamu bagikan nanti akan muncul di sini.</p>
            </div>

            {{-- EMPTY STATE --}}
            @if(auth()->id() === $user->id)
                <div id="content-saved" class="empty-feed-container" style="text-align: center; padding: 80px 20px; color: #726255; display: none;">
                    <div style="width: 80px; height: 80px; border-radius: 50%; border: 2px solid #726255; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto; opacity: 0.7;">
                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m19 21-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                        </svg>
                    </div>
                    <h2 style="font-family: serif; font-size: 1.5rem; margin: 0 0 8px 0; color: #4e443c;">Belum Ada Inspirasi Tersimpan</h2>
                    <p style="margin: 0; font-size: 0.9rem; opacity: 0.8; max-width: 300px; margin: 0 auto;">Postingan interior orang lain yang kamu simpan akan terkumpul di sini.</p>
                </div>
            @endif

        </div>

    </main>

    {{-- JAVASCRIPT UNTUK INTERAKSI PERGANTIAN TAB --}}
    <script>
        function switchTab(tabName) {
            // Ambil elemen tombol tab
            const tabPosts = document.getElementById('tab-posts');
            const tabSaved = document.getElementById('tab-saved');
            
            // Ambil elemen konten box
            const contentPosts = document.getElementById('content-posts');
            const contentSaved = document.getElementById('content-saved');

            if (tabName === 'posts') {
                // Atur style tombol aktif (Postingan)
                tabPosts.style.borderTopColor = '#111111';
                tabPosts.style.color = '#111111';
                
                // Atur style tombol non-aktif (Tersimpan)
                if(tabSaved) {
                    tabSaved.style.borderTopColor = 'transparent';
                    tabSaved.style.color = '#8e8e8e';
                }

                // Munculkan konten Postingan, sembunyikan Tersimpan
                contentPosts.style.display = 'block';
                if(contentSaved) contentSaved.style.display = 'none';

            } else if (tabName === 'saved') {
                // Atur style tombol aktif (Tersimpan)
                if(tabSaved) {
                    tabSaved.style.borderTopColor = '#111111';
                    tabSaved.style.color = '#111111';
                }
                
                // Atur style tombol non-aktif (Postingan)
                tabPosts.style.borderTopColor = 'transparent';
                tabPosts.style.color = '#8e8e8e';

                // Munculkan konten Tersimpan, sembunyikan Postingan
                contentPosts.style.display = 'none';
                if(contentSaved) contentSaved.style.display = 'block';
            }
        }
    </script>
@endsection