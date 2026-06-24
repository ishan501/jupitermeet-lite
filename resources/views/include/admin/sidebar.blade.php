@php
$currentRouteName = Route::currentRouteName();
@endphp

<aside class="navbar navbar-vertical navbar-expand-lg">
    <div class="container-fluid">
        <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
            aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand navbar-brand-autodark">
            <a href="{{ route('admin.dashboard') }}">
                @if (getThemeFromSession() == 'light')
                    <img src="{{ asset('/storage/images/' . getSetting('PRIMARY_LOGO')) }}" width="175" loading="lazy"
                        alt="{{ __('Logo') }}" class="logo-image">
                @else
                    <img src="{{ asset('/storage/images/' . getSetting('SECONDARY_LOGO')) }}" width="175" loading="lazy"
                        alt="{{ __('Logo') }}" class="logo-image">
                @endif
            </a>
        </h1>

        <div class="navbar-nav flex-row d-lg-none">
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                    aria-label="Open user menu" aria-expanded="false">
                    <div class="col-auto">
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
                    </div>
                    <div class="d-none d-xl-block ps-2">
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

        <div class="collapse navbar-collapse" id="sidebar-menu">
            <ul class="navbar-nav pt-lg-3">
                <li class="nav-item {{ $currentRouteName == 'admin.dashboard' ? 'active' : '' }}">
                    <a class="nav-link {{ $currentRouteName == 'admin.dashboard' ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-dashboard">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 13m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M13.45 11.55l2.05 -2.05" />
                                <path d="M6.4 20a9 9 0 1 1 11.2 0z" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            {{ __('Dashboard') }}
                        </span>
                    </a>
                </li>
                <li class="nav-item {{ $currentRouteName == 'admin.meeting' ? 'active' : '' }}">
                    <a class="nav-link {{ $currentRouteName == 'admin.meeting' ? 'active' : '' }}"
                        href="{{ route('admin.meeting') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-brand-zoom">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M17.011 9.385v5.128l3.989 3.487v-12z" />
                                <path
                                    d="M3.887 6h10.08c1.468 0 3.033 1.203 3.033 2.803v8.196a.991 .991 0 0 1 -.975 1h-10.373c-1.667 0 -2.652 -1.5 -2.652 -3l.01 -8a.882 .882 0 0 1 .208 -.71a.841 .841 0 0 1 .67 -.287z" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            {{ __('Meetings') }}
                        </span>
                    </a>
                </li>
                <li class="nav-item {{ str_contains($currentRouteName, 'admin.user') ? 'active' : '' }}">
                    <a class="nav-link {{ str_contains($currentRouteName, 'admin.user') ? 'active' : '' }}"
                        href="{{ route('admin.user') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-users">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            {{ __('Users') }}
                        </span>
                    </a>
                </li>
                <li class="nav-item {{ str_contains($currentRouteName, 'admin.setting') ? 'active' : '' }}">
                    <a href="{{ route('admin.setting') }}"
                        class="nav-link {{ str_contains($currentRouteName, 'admin.setting') ? 'active' : '' }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-settings">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" />
                                <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            {{ __('Settings') }}
                        </span>
                    </a>
                </li>
                <li
                    class="nav-item dropdown {{ in_array($currentRouteName, ['admin.plan', 'admin.plan.create', 'admin.coupon', 'admin.coupon.create', 'admin.coupon.edit', 'admin.taxrate', 'admin.taxrate.create', 'admin.taxrate.edit', 'admin.transaction', 'admin.payment-gateway.stripe', 'admin.payment-gateway.paypal', 'admin.payment-gateway.paystack', 'admin.payment-gateway.razorpay', 'admin.payment-gateway.mollie']) ? 'active' : '' }}">
                    <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown"
                        data-bs-auto-close="false" role="button" aria-expanded="false">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-businessplan">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M16 6m-5 0a5 3 0 1 0 10 0a5 3 0 1 0 -10 0" />
                                <path d="M11 6v4c0 1.657 2.239 3 5 3s5 -1.343 5 -3v-4" />
                                <path d="M11 10v4c0 1.657 2.239 3 5 3s5 -1.343 5 -3v-4" />
                                <path d="M11 14v4c0 1.657 2.239 3 5 3s5 -1.343 5 -3v-4" />
                                <path d="M7 9h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" />
                                <path d="M5 15v1m0 -8v1" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            {{ __('Manage Payments') }}
                        </span>
                    </a>
                    <div
                        class="dropdown-menu {{ in_array($currentRouteName, ['admin.plan', 'admin.plan.create', 'admin.coupon', 'admin.coupon.create', 'admin.coupon.edit', 'admin.taxrate', 'admin.taxrate.create', 'admin.taxrate.edit', 'admin.transaction', 'admin.payment-gateway.stripe', 'admin.payment-gateway.paypal', 'admin.payment-gateway.paystack', 'admin.payment-gateway.razorpay', 'admin.payment-gateway.mollie']) ? 'show' : '' }}">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                <a class="dropdown-item {{ str_contains($currentRouteName, 'admin.payment-gateway') ? 'active' : '' }}"
                                    href="{{ route('admin.payment-gateway.stripe') }}">
                                    {{ __('Payment Gateways') }} <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                        height="16" fill="currentColor" class="bi bi-gem" viewBox="0 0 16 16">
                                        <path
                                            d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
                                    </svg>
                                </a>
                                <a class="dropdown-item {{ str_contains($currentRouteName, 'admin.plan') ? 'active' : '' }}"
                                    href="{{ route('admin.plan') }}">
                                    {{ __('Plans') }} <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-gem" viewBox="0 0 16 16">
                                        <path
                                            d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
                                    </svg>
                                </a>
                                <a class="dropdown-item {{ str_contains($currentRouteName, 'admin.coupon') ? 'active' : '' }}"
                                    href="{{ route('admin.coupon') }}">
                                    {{ __('Coupons') }} <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-gem" viewBox="0 0 16 16">
                                        <path
                                            d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
                                    </svg>
                                </a><a
                                    class="dropdown-item {{ str_contains($currentRouteName, 'admin.taxrate') ? 'active' : '' }}"
                                    href="{{ route('admin.taxrate') }}">
                                    {{ __('Tax Rates') }} <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-gem" viewBox="0 0 16 16">
                                        <path
                                            d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
                                    </svg>
                                </a><a
                                    class="dropdown-item {{ $currentRouteName == 'admin.transaction' ? 'active' : '' }}"
                                    href="{{ route('admin.transaction') }}">
                                    {{ __('Transactions') }} <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                        height="16" fill="currentColor" class="bi bi-gem" viewBox="0 0 16 16">
                                        <path
                                            d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item {{ str_contains($currentRouteName, 'admin.email-template') ? 'active' : '' }}">
                    <a class="nav-link {{ str_contains($currentRouteName, 'admin.email-template') ? 'active' : '' }}"
                        href="{{ route('admin.email-template') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-mail-code">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M11 19h-6a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v6" />
                                <path d="M3 7l9 6l9 -6" />
                                <path d="M20 21l2 -2l-2 -2" />
                                <path d="M17 17l-2 2l2 2" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            {{ __('Email Templates') }}
                        </span>
                    </a>
                </li>
                <li class="nav-item {{ str_contains($currentRouteName, 'admin.language') ? 'active' : '' }}">
                    <a class="nav-link {{ str_contains($currentRouteName, 'admin.language') ? 'active' : '' }}"
                        href="{{ route('admin.language') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-language">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 5h7" />
                                <path d="M9 3v2c0 4.418 -2.239 8 -5 8" />
                                <path d="M5 9c0 2.144 2.952 3.908 6.7 4" />
                                <path d="M12 20l4 -9l4 9" />
                                <path d="M19.1 18h-6.2" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            {{ __('Languages') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-gem ms-1" viewBox="0 0 16 16">
                                <path
                                    d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
                            </svg>
                        </span>
                    </a>
                </li>
                <li class="nav-item {{ $currentRouteName == 'admin.signaling-server' ? 'active' : '' }}">
                    <a class="nav-link {{ $currentRouteName == 'admin.signaling-server' ? 'active' : '' }}"
                        href="{{ route('admin.signaling-server') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-cell-signal-5">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M20 20h-15.269a.731 .731 0 0 1 -.517 -1.249l14.537 -14.5137a.731 .731 0 0 1 1.249 .517v15.269z" />
                                <path d="M16 7v13" />
                                <path d="M12 20v-9" />
                                <path d="M8 20v-5" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            {{ __('Signaling Server') }}
                        </span>
                    </a>
                </li>
                <li class="nav-item {{ str_contains($currentRouteName, 'admin.page') ? 'active' : '' }}">
                    <a class="nav-link {{ str_contains($currentRouteName, 'admin.page') ? 'active' : '' }}"
                        href="{{ route('admin.page') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-book">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                <path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                <path d="M3 6l0 13" />
                                <path d="M12 6l0 13" />
                                <path d="M21 6l0 13" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            {{ __('Pages') }} <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-gem" viewBox="0 0 16 16">
                                <path
                                    d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
                            </svg>

                        </span>
                    </a>
                </li>
                <li class="nav-item {{ str_contains($currentRouteName, 'admin.addon') ? 'active' : '' }}">
                    <a class="nav-link {{ str_contains($currentRouteName, 'admin.addon') ? 'active' : '' }}"
                        href="{{ route('admin.addon') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-gift">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M3 9a1 1 0 0 1 1 -1h16a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-16a1 1 0 0 1 -1 -1l0 -2" />
                                <path d="M12 8l0 13" />
                                <path d="M19 12v7a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-7" />
                                <path
                                    d="M7.5 8a2.5 2.5 0 0 1 0 -5a4.8 8 0 0 1 4.5 5a4.8 8 0 0 1 4.5 -5a2.5 2.5 0 0 1 0 5" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            {{ __('Addons') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-gem ms-1" viewBox="0 0 16 16">
                                <path
                                    d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
                            </svg>

                        </span>
                    </a>
                </li>
                <li class="nav-item {{ str_contains($currentRouteName, 'admin.plugin') ? 'active' : '' }}">
                    <a class="nav-link {{ str_contains($currentRouteName, 'admin.plugin') ? 'active' : '' }}"
                        href="{{ route('admin.plugin') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-hexagons">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 18v-5l4 -2l4 2v5l-4 2z" />
                                <path d="M8 11v-5l4 -2l4 2v5" />
                                <path d="M12 13l4 -2l4 2v5l-4 2l-4 -2" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            {{ __('Plugins') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-gem ms-1" viewBox="0 0 16 16">
                                <path
                                    d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
                            </svg>

                        </span>
                    </a>
                </li>
                <li class="nav-item {{ $currentRouteName == 'admin.activity-log' ? 'active' : '' }}">
                    <a class="nav-link {{ $currentRouteName == 'admin.activity-log' ? 'active' : '' }}"
                        href="{{ route('admin.activity-log') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-logs">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 12h.01" />
                                <path d="M4 6h.01" />
                                <path d="M4 18h.01" />
                                <path d="M8 18h2" />
                                <path d="M8 12h2" />
                                <path d="M8 6h2" />
                                <path d="M14 6h6" />
                                <path d="M14 12h6" />
                                <path d="M14 18h6" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            {{ __('Activity Logs') }}
                        </span>
                    </a>
                </li>
                <li class="nav-item {{ $currentRouteName == 'admin.manage-update' ? 'active' : '' }}">
                    <a class="nav-link {{ $currentRouteName == 'admin.manage-update' ? 'active' : '' }}"
                        href="{{ route('admin.manage-update') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-progress-down">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M10 20.777a8.942 8.942 0 0 1 -2.48 -.969" />
                                <path d="M14 3.223a9.003 9.003 0 0 1 0 17.554" />
                                <path d="M4.579 17.093a8.961 8.961 0 0 1 -1.227 -2.592" />
                                <path d="M3.124 10.5c.16 -.95 .468 -1.85 .9 -2.675l.169 -.305" />
                                <path d="M6.907 4.579a8.954 8.954 0 0 1 3.093 -1.356" />
                                <path d="M12 9v6" />
                                <path d="M15 12l-3 3l-3 -3" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            {{ __('Manage Updates') }} <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-gem" viewBox="0 0 16 16">
                                <path
                                    d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
                            </svg>
                        </span>
                    </a>
                </li>
                <li class="nav-item {{ $currentRouteName == 'admin.license' ? 'active' : '' }}">
                    <a class="nav-link {{ $currentRouteName == 'admin.license' ? 'active' : '' }}"
                        href="{{ route('admin.license') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-certificate">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M15 15m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                <path d="M13 17.5v4.5l2 -1.5l2 1.5v-4.5" />
                                <path
                                    d="M10 19h-5a2 2 0 0 1 -2 -2v-10c0 -1.1 .9 -2 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -1 1.73" />
                                <path d="M6 9l12 0" />
                                <path d="M6 12l3 0" />
                                <path d="M6 15l2 0" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            {{ __('Manage License') }} <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-gem" viewBox="0 0 16 16">
                                <path
                                    d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
                            </svg>

                        </span>
                    </a>
                </li>
            </ul>
            <div class="mt-auto p-3 w-100 d-none d-lg-block jm-promo-container">
            </div>
        </div>
    </div>
</aside>