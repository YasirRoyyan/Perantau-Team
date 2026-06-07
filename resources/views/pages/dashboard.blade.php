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
            
            <a href="{{ route('user.profile', $user->name) }}" style="text-decoration: none; display: block; margin: 0 auto 15px auto; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='scale(1)'">
                @if ($user->avatar)
                    <div class="dashboard-avatar-wrapper" style="width: 150px; height: 150px; margin: 0 auto; flex-shrink: 0;">
                        <img src="{{ asset('storage/' . $user->avatar) }}" 
                             alt="Foto Profil {{ $displayName }}" 
                             style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; aspect-ratio: 1/1; display: block; border: 2px solid #d17a22;">
                    </div>
                @else

                    <div class="dashboard-avatar" style="margin: 0 auto;">{{ strtoupper(substr($displayName, 0, 1)) }}</div>
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
                <div class="dashboard-cover-top">
                    <span class="dashboard-brand">Interiorgram</span>
                    <label class="dashboard-search">
                        <span class="sr-only">Cari inspirasi interior</span>
                        <input type="search" placeholder="Cari">
                    </label>
                    <button type="button" class="dashboard-upload">
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
@endsection