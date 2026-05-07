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

        <a href="{{ asset($result['image']) }}" class="download-link" id="download-link" download="interiology-hasil-{{ $type }}.png">Download Gambar di sini</a>
    </main>
@endsection
