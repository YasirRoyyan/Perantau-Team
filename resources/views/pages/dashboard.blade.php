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
    @endphp

    <main class="dashboard-page">
        <aside class="dashboard-profile-card" aria-label="Profil pengguna">
            <div class="dashboard-avatar">{{ strtoupper(substr($displayName, 0, 1)) }}</div>
            <h1>{{ $handle }}</h1>
            <p>{{ $bio }}</p>

            <dl class="dashboard-social-stats">
                <div>
                    <dt>Postingan</dt>
                    <dd>37</dd>
                </div>
                <div>
                    <dt>Mengikuti</dt>
                    <dd>1.3k</dd>
                </div>
                <div>
                    <dt>Pengikut</dt>
                    <dd>4.0k</dd>
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
