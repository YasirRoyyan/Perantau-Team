@extends('layouts.app', [
    'title' => 'Interiology - Kenali Selera Desain Ruanganmu',
    'styles' => ['assets/css/home.css'],
])

@section('body')
    <div class="home-page">
        <header class="hero-shell">
            @include('partials.nav', ['home' => true])

            <section class="hero">
                <div class="hero-content">
                    <h1>Kenali selera desain ruangan kamu di Interiology</h1>
                    <p>Jawab beberapa pertanyaan singkat dan lihat rekomendasi ruang tamu yang sesuai dengan kepribadianmu.</p>
                    <a href="{{ route('prepare') }}" class="btn-primary">Cari Selera mu!</a>
                </div>
            </section>
        </header>

        <section class="cara-kerja" id="cara-kerja">
            <h2>Bagaimana cara untuk menentukan selera mu?</h2>

            <div class="steps-container">
                <div class="step-card step-card-1">
                    <div class="icon-box"><img src="{{ asset('assets/icons/icon-sofa.png') }}" alt="Sofa"></div>
                    <p>Mulai Asesmen</p>
                </div>
                <div class="step-card step-card-2">
                    <div class="icon-box"><img src="{{ asset('assets/icons/icon-table.png') }}" alt="Nightstand"></div>
                    <p>Jawab beberapa pertanyaan</p>
                </div>
                <div class="step-card step-card-3">
                    <div class="icon-box"><img src="{{ asset('assets/icons/icon-tv.png') }}" alt="Television"></div>
                    <p>Tampilan Interior Muncul Sesuai Jawaban</p>
                </div>
                <div class="step-card step-card-4">
                    <div class="icon-box"><img src="{{ asset('assets/icons/icon-verified.png') }}" alt="Check"></div>
                    <p>Selamat Seleramu berhasil ditemukan!</p>
                </div>
            </div>

            <div class="showcase-image">
                <img src="{{ asset('assets/images/img-container.png') }}" alt="Contoh Ruangan">
            </div>

            <div class="call-to-action" id="kustom-ruangan">
                <h2>Tertarik untuk membuat ruangan sendiri?</h2>
                <a href="{{ route('prepare') }}" class="btn-primary btn-dark">Mulai Sekarang!</a>
            </div>
        </section>

        <section class="gallery" id="interiorgram">
            <div class="gallery-item">
                <img src="{{ asset('assets/images/img3.png') }}" alt="Inspirasi 1">
            </div>
            <div class="gallery-item">
                <img src="{{ asset('assets/images/img-5.png') }}" alt="Inspirasi 2">
            </div>
            <div class="gallery-item">
                <img src="{{ asset('assets/images/img-4.png') }}" alt="Inspirasi 3">
            </div>
        </section>

        <footer class="footer" id="tentang">
            <div class="footer-content">
                <h2>Tentang Interiology</h2>
                <p class="footer-desc">
                    Website ini dirancang untuk membantu kamu menemukan gaya ruang tamu yang paling sesuai dengan
                    kepribadian dan preferensimu. Melalui asesmen interaktif dan visualisasi sederhana, kami berharap kamu
                    bisa lebih percaya diri dalam menentukan pilihan desain interior.
                </p>
                <p class="footer-location">Bandung, Jawa Barat, Indonesia</p>
                <div class="footer-socials">
                    <a href="#"><img src="{{ asset('assets/icons/wa.png') }}" alt="WhatsApp"></a>
                    <a href="#"><img src="{{ asset('assets/icons/ig.png') }}" alt="Instagram"></a>
                </div>
            </div>
        </footer>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/home.js') }}"></script>
@endpush
