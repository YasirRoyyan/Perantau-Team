@extends('layouts.app', [
    'title' => 'Interiology - Hasil Asesmen',
    'styles' => ['assets/css/shared.css', 'assets/css/result.css'],
])

@section('body')
    @include('partials.nav')

    <main class="hasil-section">
        <div class="hasil-image">
            <img id="hasil-img" src="{{ asset($result['image']) }}" alt="Interior hasil {{ $type }}">
        </div>

        <p class="hasil-label">Tipe kamu adalah</p>
        <h1 class="hasil-title" id="hasil-title">{{ $result['title'] }}</h1>
        <p class="hasil-desc" id="hasil-desc">{{ $result['description'] }}</p>

        <div class="hasil-buttons">
            <a href="{{ route('home') }}" class="btn-menu">Menu Utama</a>
            <a href="{{ route('prepare') }}" class="btn-tes-baru">Mulai Tes Baru</a>
        </div>

        <!-- <div style="text-align: center; margin-top: 25px; margin-bottom: 5px;">
            <a href="{{ route('custom-room') }}" class="download-link" style="color: #d17a22; font-weight: 600; text-decoration: underline;">
                Tertarik mengatur tata letak furnitur tipe ini? Coba Fitur Kustom Ruangan di sini
            </a>
        </div> -->

        <div style="text-align: center; margin-top: 30px; display: flex; flex-direction: column; gap: 8px; align-items: center;">
            
            <a href="{{ route('custom-room', ['style' => $type]) }}" class="download-link" style="color: #311e0b; font-weight: 600; text-decoration: underline; margin: 0; padding: 0;">
                Tertarik menyusun ruangan anda sendiri? Coba disini!
            </a>

            <a href="{{ asset($result['image']) }}" class="download-link" id="download-link" download="interiology-hasil-{{ $type }}.png" style="margin: 0; padding: 0;">
                Download Gambar di sini
            </a>
            
        </div>
    </main>
@endsection
