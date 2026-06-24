<header class="navbar navbar-expand-md d-none d-lg-flex d-print-none">
    <div class="container-xl">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
            aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-nav flex-row order-md-last">
            <li class="nav-item {{ Route::is('dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-home">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                            <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                            <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                        </svg>
                    </span>
                    <span class="nav-link-title">
                        {{ __('Dashboard') }}
                    </span>
                </a>
            </li>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                    aria-label="Open user menu">
                    @if (getAuthUserInfo('avatar') && file_exists(public_path('storage/avatars/' . getAuthUserInfo('avatar'))))
                        <div class="col-auto">
                            <span class="avatar avatar-sm"
                                style="background-image: url('{{ asset('storage/avatars/' . getAuthUserInfo('avatar')) }}')"></span>
                        </div>
                    @else
                        <div class="col-auto">
                            <span class="avatar avatar-sm"
                                style="background-image: url('{{ asset('/images/blank.jpeg') }}')"></span>
                        </div>
                    @endif
                    <div class="d-none d-sm-block ps-2">
                        <div>{{ getAuthUserInfo('full_name') }}</div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <a href="{{ route('user.profile.basic') }}" class="dropdown-item">{{ __('Profile') }}</a>
                    <a href="" class="dropdown-item"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
        <div class="collapse navbar-collapse" id="navbar-menu">
        </div>
    </div>
</header>
