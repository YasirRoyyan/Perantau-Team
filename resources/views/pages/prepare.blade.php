@extends('layouts.app', [
    'title' => 'Interiology - Persiapan Asesmen',
    'styles' => ['assets/css/shared.css', 'assets/css/prepare.css'],
])

@section('body')
    @include('partials.nav')

    <main class="prepare-section">
        <a href="{{ route('home') }}" class="back-button" aria-label="Kembali ke beranda">
            <span class="back-icon" aria-hidden="true"></span>
        </a>

        <div class="prepare-box">
            @include('partials.alerts')

            <h1>
                Kamu akan diberikan 10 pertanyaan<br>
                dan tampilan ruangan akan berubah sesuai<br>
                dengan jawabanmu...
            </h1>
            <h2>Apakah kamu siap?</h2>
            <div class="prepare-actions">
                <a href="{{ route('assessment.start') }}" class="btn-mulai">Mulai Sekarang!</a>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/prepare.js') }}"></script>
@endpush
