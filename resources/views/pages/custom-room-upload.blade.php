@extends('layouts.app', [
    'title' => 'Interiology - Posting Ruangan',
    'bodyClass' => 'custom-room-shell',
    'styles' => ['assets/css/shared.css', 'assets/css/custom-room.css'],
])

@section('body')
    @include('partials.nav')

    <main class="custom-room-upload-page">
        <section class="custom-room-upload-card">
            <div class="custom-room-upload-preview">
                <img src="{{ $draft['image'] }}" alt="Hasil kustom ruangan">
            </div>

            <form class="custom-room-upload-form" method="POST" action="{{ route('custom-post.store') }}">
                @csrf
                <input type="hidden" name="image" value="{{ $draft['image'] }}">

                <div class="custom-room-upload-head">
                    <img
                        class="avatar"
                        src="{{ $user?->avatar && \Storage::disk('public')->exists($user->avatar) ? \Storage::url($user->avatar) : asset('assets/images/profile-interiorgram.jpeg') }}"
                        alt="Avatar {{ $user?->name ?? 'Pengguna' }}"
                    >
                    <div class="custom-room-upload-title">
                        <h2>Buat Postingan Baru</h2>
                        <p>{{ '@' . ($user?->name ?? 'pengguna') }}</p>
                    </div>
                </div>

                <p class="custom-room-upload-style">
                    {{ $styleLabel }}
                    <br>
                    {{ $totalPosts }} postingan dibagikan
                </p>

                @include('partials.alerts')

                <textarea
                    name="caption"
                    maxlength="1000"
                    placeholder="Tulis deskripsi / caption inspirasi ruangan interior kamu di sini..."
                >{{ old('caption') }}</textarea>

                <div class="custom-room-upload-actions">
                    <a href="{{ route('custom-room', ['style' => $draft['style']]) }}">Batal</a>
                    <button type="submit">Post / Bagikan</button>
                </div>
            </form>
        </section>
    </main>
@endsection
