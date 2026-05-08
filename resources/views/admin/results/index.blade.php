@extends('layouts.app', [
    'title' => 'Interiology - Kelola Hasil',
    'styles' => ['assets/css/shared.css', 'assets/css/auth.css', 'assets/css/admin.css'],
])

@section('body')
    @include('partials.nav')

    <main class="dashboard-page">
        <section class="dashboard-hero">
            <div>
                <p class="eyebrow">Admin</p>
                <h1>Hasil Desain</h1>
                <p class="auth-copy">Hasil rekomendasi diambil dari database dan bisa diubah.</p>
            </div>
            <div class="dashboard-actions">
                <a href="{{ route('admin.content.index') }}" class="btn-outline">Konten</a>
                <a href="{{ route('admin.results.create') }}" class="btn-submit">Tambah Hasil</a>
            </div>
        </section>

        <section class="panel">
            @include('partials.alerts')
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Urutan</th>
                            <th>Key</th>
                            <th>Judul</th>
                            <th>Gambar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($results as $result)
                            <tr>
                                <td>{{ $result->sort_order }}</td>
                                <td>{{ $result->style_key }}</td>
                                <td>{{ $result->title }}</td>
                                <td>{{ $result->image }}</td>
                                <td class="admin-actions">
                                    <a href="{{ route('admin.results.edit', $result) }}" class="btn-outline">Edit</a>
                                    <form action="{{ route('admin.results.destroy', $result) }}" method="POST">
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
