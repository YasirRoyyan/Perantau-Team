@extends('layouts.app', [
    'title' => $question->exists ? 'Interiology - Edit Pertanyaan' : 'Interiology - Tambah Pertanyaan',
    'styles' => ['assets/css/shared.css', 'assets/css/auth.css', 'assets/css/admin.css'],
])

@section('body')
    @include('partials.nav')

    <main class="profile-page">
        <section class="profile-card">
            <div class="profile-heading">
                <div>
                    <p class="eyebrow">Admin</p>
                    <h1>{{ $question->exists ? 'Edit Pertanyaan' : 'Tambah Pertanyaan' }}</h1>
                    <p class="auth-copy">Data ini akan dipakai langsung dalam alur asesmen.</p>
                </div>
                <a href="{{ route('admin.questions.index') }}" class="btn-outline">Kembali</a>
            </div>

            @include('partials.alerts')

            <form action="{{ $question->exists ? route('admin.questions.update', $question) : route('admin.questions.store') }}" method="POST" class="profile-form">
                @csrf
                @if ($question->exists)
                    @method('PUT')
                @endif

                <label class="full-span">
                    Pertanyaan
                    <input name="question" value="{{ old('question', $question->question) }}" required>
                </label>

                @php($options = old('options', $question->options ?: ['', '', '', '']))
                @for ($i = 0; $i < max(4, count($options)); $i++)
                    <label>
                        Pilihan {{ $i + 1 }}
                        <input name="options[]" value="{{ $options[$i] ?? '' }}" required>
                    </label>
                @endfor

                <label>
                    Gambar
                    <input name="image" value="{{ old('image', $question->image) }}" required>
                </label>
                <label>
                    Judul Intro
                    <input name="intro_title" value="{{ old('intro_title', $question->intro_title) }}">
                </label>
                <label>
                    Urutan
                    <input type="number" name="sort_order" value="{{ old('sort_order', $question->sort_order) }}" required>
                </label>

                <button class="btn-submit full-span" type="submit">Simpan Pertanyaan</button>
            </form>
        </section>
    </main>
@endsection
