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
                    <h1>{{ $hero['title'] }}</h1>
                    <p>{{ $hero['description'] }}</p>
                    <a href="{{ route('prepare') }}" class="btn-primary">{{ $hero['button'] }}</a>
                </div>
            </section>
        </header>

        <section class="cara-kerja" id="cara-kerja">
            <h2>{{ $workflowTitle }}</h2>

            <div class="steps-container">
                @foreach ($workflowSteps as $step)
                    <div class="step-card {{ $step['class'] }}">
                        <div class="icon-box"><img src="{{ asset($step['icon']) }}" alt="{{ $step['alt'] }}"></div>
                        <p>{{ $step['label'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="showcase-image">
                <img src="{{ asset($showcase['image']) }}" alt="{{ $showcase['alt'] }}">
            </div>

            <div class="call-to-action" id="kustom-ruangan">
                <h2>{{ $customRoomCta['title'] }}</h2>
                <a href="{{ route('prepare') }}" class="btn-primary btn-dark">{{ $customRoomCta['button'] }}</a>
            </div>
        </section>

        <section class="gallery" id="interiorgram">
            @foreach ($galleryImages as $galleryImage)
                <div class="gallery-item">
                    <img src="{{ asset($galleryImage['image']) }}" alt="{{ $galleryImage['alt'] }}">
                </div>
            @endforeach
        </section>

        <footer class="footer" id="tentang">
            <div class="footer-content">
                <h2>{{ $footer['title'] }}</h2>
                <p class="footer-desc">{{ $footer['description'] }}</p>
                <p class="footer-location">{{ $footer['location'] }}</p>
                <div class="footer-socials">
                    @foreach ($footer['socials'] as $social)
                        <a href="{{ $social['url'] }}"><img src="{{ asset($social['icon']) }}" alt="{{ $social['label'] ?? $social['alt'] ?? 'Sosial' }}"></a>
                    @endforeach
                </div>
            </div>
        </footer>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/home.js') }}"></script>
@endpush
