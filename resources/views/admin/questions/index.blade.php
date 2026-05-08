@extends('layouts.app', [
    'title' => 'Interiology - Kelola Pertanyaan',
    'styles' => ['assets/css/shared.css', 'assets/css/auth.css', 'assets/css/admin.css'],
])

@section('body')
    @include('partials.nav')

    <main class="dashboard-page">
        <section class="dashboard-hero">
            <div>
                <p class="eyebrow">Admin</p>
                <h1>Pertanyaan Asesmen</h1>
                <p class="auth-copy">Pertanyaan dan pilihan jawaban dibaca langsung dari database.</p>
            </div>
            <div class="dashboard-actions">
                <a href="{{ route('admin.content.index') }}" class="btn-outline">Konten</a>
                <a href="{{ route('admin.questions.create') }}" class="btn-submit">Tambah Pertanyaan</a>
            </div>
        </section>

        <section class="panel">
            @include('partials.alerts')
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Urutan</th>
                            <th>Pertanyaan</th>
                            <th>Pilihan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($questions as $question)
                            <tr>
                                <td>{{ $question->sort_order }}</td>
                                <td>{{ $question->question }}</td>
                                <td>{{ implode(', ', $question->options ?? []) }}</td>
                                <td class="admin-actions">
                                    <a href="{{ route('admin.questions.edit', $question) }}" class="btn-outline">Edit</a>
                                    <form action="{{ route('admin.questions.destroy', $question) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-outline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </main>
@endsection
