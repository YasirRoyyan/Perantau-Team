@extends('layouts.app', [
    'title' => 'Interiology - Kustom Ruangan',
    'bodyClass' => 'custom-room-shell',
    'styles' => ['assets/css/shared.css', 'assets/css/custom-room.css'],
])

@section('body')
    @include('partials.nav')

    <main
        class="custom-room-page"
        data-custom-room
        data-default-style="{{ $roomConfig['activeStyle'] }}"
        data-draft-url="{{ route('custom-room.draft') }}"
        data-upload-url="{{ route('custom-room.upload') }}"
    >
        <script type="application/json" data-custom-room-config>@json($roomConfig)</script>
        <section class="custom-room-builder" data-builder-screen>
            <a href="{{ route('home') }}" class="custom-room-back" aria-label="Kembali">
                <span aria-hidden="true"></span>
            </a>

            <h1>Kustom Ruangan Kamu</h1>

            <div class="custom-room-layout">
                <figure class="custom-room-preview">
                    <div class="custom-room-canvas" data-drop-zone>
                        <img src="{{ $roomConfig['styles'][$roomConfig['activeStyle']]['background'] }}" alt="Preview ruangan kosong" data-room-preview>
                        <div class="custom-room-layer" data-room-layer></div>
                        <p class="custom-room-drop-hint" data-drop-hint>Tarik item ke ruangan ini</p>
                    </div>
                    <div class="custom-room-item-toolbar" data-item-toolbar hidden aria-label="Kontrol item terpilih">
                        <span data-item-toolbar-label>Pilih item untuk mengatur ukuran</span>
                        <div class="custom-room-item-toolbar-actions">
                            <button type="button" data-item-size="decrease" aria-label="Perkecil item">-</button>
                            <button type="button" data-item-size="increase" aria-label="Perbesar item">+</button>
                            <button type="button" data-item-flip="horizontal">Flip H</button>
                            <button type="button" data-item-flip="vertical">Flip V</button>
                            <button type="button" data-item-reset>Reset</button>
                            <button type="button" data-item-delete>Hapus</button>
                        </div>
                    </div>
                    <figcaption>*Furniture yang muncul sesuai dengan hasil tes kamu yang akurat</figcaption>
                    <figcaption>*Gunakan key delete dan backspace untuk remove item</figcaption>
                </figure>

                <aside class="custom-room-choice" aria-label="Pilihan item ruangan">
                    <h2>Pilih furniture yang kamu mau</h2>
                    <p>Tarik item pilihanmu ke gambar ruangan kosong, lalu geser posisinya sampai sesuai.</p>

                    <div class="custom-room-style-tabs" role="tablist" aria-label="Style ruangan">
                        @foreach ($roomConfig['styles'] as $style)
                            <button
                                type="button"
                                class="{{ $style['key'] === $roomConfig['activeStyle'] ? 'is-active' : '' }}"
                                data-room-style="{{ $style['key'] }}"
                                role="tab"
                                aria-selected="{{ $style['key'] === $roomConfig['activeStyle'] ? 'true' : 'false' }}"
                            >
                                {{ $style['label'] }}
                            </button>
                        @endforeach
                    </div>

                    <div class="custom-room-tabs" role="tablist" aria-label="Kategori item ruangan">
                        @foreach ($roomConfig['categories'] as $categoryKey => $category)
                            <button
                                type="button"
                                class="{{ $categoryKey === 'chairs' ? 'is-active' : '' }}"
                                data-item-category="{{ $categoryKey }}"
                                role="tab"
                                aria-selected="{{ $categoryKey === 'chairs' ? 'true' : 'false' }}"
                            >
                                {{ $category['label'] }}
                            </button>
                        @endforeach
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
                <img src="{{ $roomConfig['styles'][$roomConfig['activeStyle']]['background'] }}" alt="Hasil kustom ruangan">
                <div class="custom-room-layer" data-result-layer></div>
            </div>

            <div class="custom-room-result-copy">
                <h2>Ruangan yang bagus!</h2>
                <p>Posting ruanganmu ke Interiorgram dan dapatkan banyak suka dari pengguna lain!</p>
                <button type="button" class="custom-room-post" data-post-room>Posting Sekarang</button>
                <p class="custom-room-post-status" data-post-status aria-live="polite"></p>
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/custom-room.js') }}?v={{ filemtime(public_path('assets/js/custom-room.js')) }}"></script>
@endpush
