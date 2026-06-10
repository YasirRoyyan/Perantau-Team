@extends('layouts.app', [
    'title' => 'Interiology - Asesmen',
    'styles' => ['assets/css/shared.css', 'assets/css/assessment.css'],
])

@section('body')
    @include('partials.nav')

    <main class="asesmen-section">
        <a href="{{ route('assessment.back') }}" class="back-button" aria-label="Kembali ke pertanyaan sebelumnya">
            <span class="back-icon" aria-hidden="true"></span>
        </a>

        <h1 class="asesmen-title" id="asesmen-title">{{ $title }}</h1>

        <div class="asesmen-image">
            <img id="asesmen-img" src="{{ asset($image) }}" alt="Interior ruangan">
        </div>

        <p class="progress" id="progress">{{ $progress }} dari {{ $total }}</p>

        <h2 class="question" id="question">{{ $question['question'] }}</h2>

        <form class="options" id="options" action="{{ route('assessment.answer') }}" method="POST">
            @csrf
            @foreach ($question['options'] as $option)
                <button type="submit" name="option" value="{{ $loop->index }}" class="option-btn">
                    <span class="option-number">{{ $loop->iteration }}</span>
                    <span>{{ $option }}</span>
                </button>
            @endforeach
        </form>
    </main>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var confirmUnsaved = true;
            var form = document.getElementById('options');

            var disablePrompt = function () {
                confirmUnsaved = false;
            };

            var beforeUnloadHandler = function (event) {
                if (!confirmUnsaved) {
                    return;
                }
                var message = 'Data asesmen Anda belum tersimpan. Jika Anda meninggalkan halaman ini, jawaban saat ini tidak akan tersimpan.';
                event.preventDefault();
                event.returnValue = message;
                return message;
            };

            window.addEventListener('beforeunload', beforeUnloadHandler);

            if (form) {
                form.addEventListener('submit', disablePrompt);
            }
        });
    </script>
    @endpush
@endsection
