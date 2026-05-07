<nav class="navbar" @if ($home ?? false) id="navbar" @endif>
    <a class="nav-logo" href="{{ route('home') }}">Interiology</a>
    <ul class="nav-links">
        <li><a href="{{ route('home') }}">Beranda</a></li>
        <li><a href="{{ ($home ?? false) ? '#cara-kerja' : route('home').'#cara-kerja' }}">Cara Kerja</a></li>
        <li><a href="{{ ($home ?? false) ? '#kustom-ruangan' : route('home').'#kustom-ruangan' }}">Kustom Ruangan</a></li>
        <li><a href="{{ route('prepare') }}" class="{{ ($home ?? false) ? 'nav-cta' : '' }}">Cari Selera mu!</a></li>
        <li><a href="{{ ($home ?? false) ? '#interiorgram' : route('home').'#interiorgram' }}">Interiorgram</a></li>
        @guest
            <li><a href="{{ route('login') }}" class="nav-login">Login</a></li>
        @else
            <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="account-menu">
                <button type="button" class="account-trigger" aria-expanded="false" aria-haspopup="true">
                    <span class="account-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    <span class="account-name">{{ auth()->user()->name }}</span>
                    <span class="account-caret" aria-hidden="true"></span>
                </button>
                <div class="account-dropdown">
                    <a href="{{ route('profile.show') }}" class="account-dropdown-item">
                        <span class="account-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24">
                                <path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"></path>
                                <path d="M4.5 20a7.5 7.5 0 0 1 15 0"></path>
                            </svg>
                        </span>
                        Profil
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="account-dropdown-item">
                            <span class="account-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24">
                                    <path d="M9 7 4 12l5 5"></path>
                                    <path d="M4 12h11"></path>
                                    <path d="M14 4h4a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-4"></path>
                                </svg>
                            </span>
                            Keluar
                        </button>
                    </form>
                </div>
            </li>
        @endguest
    </ul>
</nav>

@push('scripts')
    <script src="{{ asset('assets/js/account-menu.js') }}"></script>
@endpush
