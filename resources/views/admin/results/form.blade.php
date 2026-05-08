@extends('layouts.app', [
    'title' => $result->exists ? 'Interiology - Edit Hasil' : 'Interiology - Tambah Hasil',
    'styles' => ['assets/css/shared.css', 'assets/css/auth.css', 'assets/css/admin.css'],
])

@section('body')
    @include('partials.nav')

    <main class="profile-page">
        <section class="profile-card">
            <div class="profile-heading">
                <div>
                    <p class="eyebrow">Admin</p>
                    <h1>{{ $result->exists ? 'Edit Hasil' : 'Tambah Hasil' }}</h1>
                    <p class="auth-copy">Urutan hasil dipakai untuk memetakan pilihan jawaban asesmen.</p>
                </div>
                <a href="{{ route('admin.results.index') }}" class="btn-outline">Kembali</a>
            </div>

            @include('partials.alerts')

            <form action="{{ $result->exists ? route('admin.results.update', $result) : route('admin.results.store') }}" method="POST" class="profile-form">
                @csrf
                @if ($result->exists)
                    @method('PUT')
                @endif

                <label>
                    Style Key
                    <input name="style_key" value="{{ old('style_key', $result->style_key) }}" required>
                </label>
                <label>
                    Judul
                    <input name="title" value="{{ old('title', $result->title) }}" required>
                </label>
                <label class="full-span">
                    Deskripsi
                    <textarea name="description" rows="5" required>{{ old('description', $result->description) }}</textarea>
                </label>
                <label>
                    Gambar
                    <input name="image" value="{{ old('image', $result->image) }}" required>
                </label>
                <label>
                    Urutan
                    <input type="number" name="sort_order" value="{{ old('sort_order', $result->sort_order) }}" required>
                </label>

                <button class="btn-submit full-span" type="submit">Simpan Hasil</button>
            </form>
        </section>
    </main>
@endsection
