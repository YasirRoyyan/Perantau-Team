@extends('layouts.app', [
    'title' => 'Interiology - Kustom Ruangan',
    'bodyClass' => 'custom-room-shell',
    'styles' => ['assets/css/shared.css', 'assets/css/custom-room.css'],
])

@section('body')
    @include('partials.nav')

    <main class="custom-room-page" data-custom-room>
        <section class="custom-room-builder" data-builder-screen>
            <a href="{{ route('home') }}" class="custom-room-back" aria-label="Kembali">
                <span aria-hidden="true"></span>
            </a>

            <h1>Kustom Ruangan Kamu</h1>

            <div class="custom-room-layout">
                <figure class="custom-room-preview">
                    <div class="custom-room-canvas" data-drop-zone>
                        <img src="{{ asset('assets/images/custom-room-empty.png') }}" alt="Preview ruangan kosong" data-room-preview>
                        <div class="custom-room-layer" data-room-layer></div>
                        <p class="custom-room-drop-hint" data-drop-hint>Tarik item ke ruangan ini</p>
                    </div>
                    <figcaption>*Furniture yang muncul sesuai dengan hasil tes kamu yang akurat</figcaption>
                    <figcaption>*Gunakan key delete dan backspace untuk remove item</figcaption>
                </figure>

                <aside class="custom-room-choice" aria-label="Pilihan item ruangan">
                    <h2>Pilih furniture yang kamu mau</h2>
                    <p>Tarik item pilihanmu ke gambar ruangan kosong, lalu geser posisinya sampai sesuai.</p>

                    <div class="custom-room-tabs" role="tablist" aria-label="Kategori item ruangan">
                        <button type="button" class="is-active" data-item-category="chairs" role="tab" aria-selected="true">Kursi</button>
                        <button type="button" data-item-category="tables" role="tab" aria-selected="false">Meja</button>
                        <button type="button" data-item-category="walls" role="tab" aria-selected="false">Hiasan</button>
                    </div>

                    <div class="custom-room-item-grid" data-item-palette aria-label="Item yang bisa ditarik ke ruangan"></div>

                    <div class="custom-room-actions">
                        <button type="button" class="custom-room-button custom-room-button-muted" data-reset-room>Ulang</button>
                        <button type="button" class="custom-room-button custom-room-button-finish" data-finish-room disabled>Selesai</button>
                    </div>
                </aside>
            </div>
        </section>

        <section class="custom-room-result" data-result-screen hidden>
            <a href="{{ route('home') }}" class="custom-room-back" aria-label="Kembali">
                <span aria-hidden="true"></span>
            </a>

            <h1>Kustom Ruangan Kamu</h1>

            <div class="custom-room-result-preview" data-result-preview>
                <img src="{{ asset('assets/images/custom-room-empty.png') }}" alt="Hasil kustom ruangan">
                <div class="custom-room-layer" data-result-layer></div>
            </div>

            <div class="custom-room-result-copy">
                <h2>Ruangan yang bagus!</h2>
                <p>Posting ruanganmu ke Interiorgram dan dapatkan banyak suka dari pengguna lain!</p>
                <label class="custom-room-caption-field">
                    <span>Catatan postingan (opsional)</span>
                    <textarea data-room-caption rows="4" placeholder="Tulis catatan singkat untuk postingan ini..."></textarea>
                </label>
                <button type="button" class="custom-room-post" data-post-room>Posting Sekarang</button>
                <p class="custom-room-post-status" data-post-status aria-live="polite"></p>
                <button type="button" data-edit-room>Kembali ke beranda atau edit</button>
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/custom-room.js') }}?v={{ filemtime(public_path('assets/js/custom-room.js')) }}"></script>
@endpush
