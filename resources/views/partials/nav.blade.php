<nav class="navbar" @if ($home ?? false) id="navbar" @endif>
    <a class="nav-logo" href="{{ route('home') }}">Interiology</a>
    <ul class="nav-links">
        @foreach ($navItems as $item)
            @php
                $authState = $item['auth_state'] ?? 'all';
                $isVisible = $authState === 'all' || ($authState === 'guest' && auth()->guest()) || ($authState === 'auth' && auth()->check());
                $href = $item['external_url'] ?? null;

                if (! $href && ! empty($item['route_name'])) {
                    $href = route($item['route_name']);

                    if (! empty($item['anchor'])) {
                        $href = ($home ?? false) && $item['route_name'] === 'home'
                            ? '#'.$item['anchor']
                            : $href.'#'.$item['anchor'];
                    }
                }

                $href = $href ?: '#';
                $classes = trim(($item['is_cta'] ?? false) && ($home ?? false) ? 'nav-cta' : '');
                $classes = trim(($authState === 'guest' ? 'nav-login ' : '').$classes);
            @endphp

            @if ($isVisible)
                <li><a href="{{ $href }}" @if ($classes) class="{{ $classes }}" @endif>{{ $item['label'] }}</a></li>
            @endif
        @endforeach

        @auth
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
        @endauth
    </ul>
</nav>

@push('scripts')
    <script src="{{ asset('assets/js/account-menu.js') }}"></script>
@endpush
