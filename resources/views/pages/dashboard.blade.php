@extends('layouts.app', [
    'title' => 'Interiology - Dashboard',
    'styles' => ['assets/css/shared.css', 'assets/css/auth.css'],
])

@section('body')
    @include('partials.nav')

    <main class="dashboard-page">
        <section class="dashboard-hero">
            <div>
                <p class="eyebrow">Dashboard</p>
                <h1>Halo, {{ $user->name }}</h1>
                <p class="auth-copy">Role kamu saat ini adalah <span class="role-badge">{{ ucfirst($user->role) }}</span>.</p>
            </div>
            <div class="dashboard-actions">
                <a href="{{ route('prepare') }}" class="btn-submit">Mulai Asesmen</a>
                <a href="{{ route('profile.show') }}" class="btn-outline">Edit Profil</a>
                @if ($user->role === 'admin')
                    <a href="{{ route('admin.content.index') }}" class="btn-outline">Kelola Konten</a>
                @endif
            </div>
        </section>

        @if ($user->role === 'admin')
            <section class="stats-grid">
                <div class="stat-box">
                    <span>Total Akun</span>
                    <strong>{{ $adminData['totalUsers'] }}</strong>
                </div>
                <div class="stat-box">
                    <span>Admin</span>
                    <strong>{{ $adminData['totalAdmins'] }}</strong>
                </div>
                <div class="stat-box">
                    <span>User</span>
                    <strong>{{ $adminData['totalRegularUsers'] }}</strong>
                </div>
                <div class="stat-box">
                    <span>Pertanyaan</span>
                    <strong>{{ $adminData['totalQuestions'] }}</strong>
                </div>
                <div class="stat-box">
                    <span>Hasil Desain</span>
                    <strong>{{ $adminData['totalResults'] }}</strong>
                </div>
                <div class="stat-box">
                    <span>Riwayat Asesmen</span>
                    <strong>{{ $adminData['totalAttempts'] }}</strong>
                </div>
            </section>

            <section class="panel">
                <h2>Kelola Website</h2>
                <div class="dashboard-actions">
                    <a href="{{ route('admin.content.index') }}" class="btn-submit">Konten Homepage</a>
                    <a href="{{ route('admin.questions.index') }}" class="btn-outline">Pertanyaan Asesmen</a>
                    <a href="{{ route('admin.results.index') }}" class="btn-outline">Hasil Desain</a>
                </div>
            </section>

            <section class="panel">
                <h2>Akun Terbaru</h2>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Kota</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($adminData['latestUsers'] as $listedUser)
                                <tr>
                                    <td>{{ $listedUser->name }}</td>
                                    <td>{{ $listedUser->email }}</td>
                                    <td>{{ ucfirst($listedUser->role) }}</td>
                                    <td>{{ $listedUser->city ?: '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="panel">
                <h2>Riwayat Asesmen Terbaru</h2>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Hasil</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($adminData['latestAttempts'] as $attempt)
                                <tr>
                                    <td>{{ $attempt->user?->name ?? '-' }}</td>
                                    <td>{{ $attempt->result_title }}</td>
                                    <td>{{ $attempt->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">Belum ada riwayat asesmen.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        @else
            <section class="panel user-panel">
                <h2>Ruang Personalmu</h2>
                <p>Lengkapi profil, lalu lanjutkan asesmen untuk menemukan gaya interior yang paling cocok.</p>
                <div class="profile-summary">
                    <span>Kota</span>
                    <strong>{{ $user->city ?: 'Belum diisi' }}</strong>
                    <span>Telepon</span>
                    <strong>{{ $user->phone ?: 'Belum diisi' }}</strong>
                </div>
            </section>

            <section class="panel">
                <h2>Riwayat Asesmenmu</h2>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Hasil</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($attempts as $attempt)
                                <tr>
                                    <td>{{ $attempt->result_title }}</td>
                                    <td>{{ $attempt->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2">Belum ada riwayat. Mulai asesmen pertamamu dulu.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        @endif
    </main>
@endsection
