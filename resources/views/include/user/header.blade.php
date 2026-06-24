<!-- Header start here -->
<header class="navbar navbar-expand-md d-print-none" style="z-index: 1030; position: relative;">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
            aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand navbar-brand-autodark">
            <a href="{{ route('home') }}" class="d-flex">
                <img src="{{ asset('/storage/images/' . getSetting('SECONDARY_LOGO')) }}" width="110" height="32" loading="lazy"
                    alt="{{ __('Logo') }}" class="logo-image">

            </a>
        </h1>
        <div class="navbar-nav flex-row order-md-last jm-header-profile align-items-center">


            @auth
                <div class="nav-item dropdown">
                    <a class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
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
                            <div class="jm-profile-name">{{ getAuthUserInfo('full_name') }}</div>
                            <div class="mt-1 small text-secondary jm-designation">{{ getAuthUserInfo('designation') }}
                            </div>
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
            @endauth


        </div>
        <div class="collapse navbar-collapse" id="navbar-menu">
            <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
                <ul class="navbar-nav ms-md-auto">
                    @auth
                        @if (getAuthUserInfo('role') == 'admin' && !Route::is('home'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-user-star">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                            <path d="M6 21v-2a4 4 0 0 1 4 -4h.5" />
                                            <path
                                                d="M17.8 20.817l-2.172 1.138a.392 .392 0 0 1 -.568 -.41l.415 -2.411l-1.757 -1.707a.389 .389 0 0 1 .217 -.665l2.428 -.352l1.086 -2.193a.392 .392 0 0 1 .702 0l1.086 2.193l2.428 .352a.39 .39 0 0 1 .217 .665l-1.757 1.707l.414 2.41a.39 .39 0 0 1 -.567 .411l-2.172 -1.138z" />
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">
                                        {{ __('Admin') }}
                                    </span>
                                </a>
                            </li>
                        @endif

                        @if (getSetting('AUTH_MODE') == 'enabled')
                            <li class="nav-item {{ Route::is('dashboard') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('dashboard') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-home">
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
                        @endif
                    @endauth
                    @if (showUpgrade() && !Route::is('home'))
                        <a href="{{ route('pricing') }}" class="btn btn-yellow Jm-upgrade-btn me-2 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-crown me-0 me-sm-2">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 6l4 6l5 -4l-2 10h-14l-2 -10l5 4z" />
                            </svg>
                            {{ __('Upgrade') }}
                        </a>
                    @else
                        @if (Route::has('pricing') && count(paymentGateways()) != 0 && getSetting('PAYMENT_MODE') == 'enabled')
                            <li class="nav-item {{ Route::is('pricing') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('pricing') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-currency-dollar">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                                            <path d="M12 3v3m0 12v3" />
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">
                                        {{ __('Pricing') }}
                                    </span>
                                </a>
                            </li>
                        @endif
                    @endif

                    @guest
                        @if (Route::has('login') && getSetting('AUTH_MODE') == 'enabled')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif
                        @if (getSetting('REGISTRATION') == 'enabled' && getSetting('AUTH_MODE') == 'enabled')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @endguest
                </ul>
            </div>
        </div>
    </div>
</header>
<!-- Header end here-->
