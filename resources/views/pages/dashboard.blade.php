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
        @endif
    </main>
@endsection
