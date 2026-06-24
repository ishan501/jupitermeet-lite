@extends('layouts.auth')

@section('title', __('Login'))

@section('content')
    <div class="page page-center">
        <div class="container container-tight py-4">
            <div class="text-center mb-4">
                <a href="{{ route('home') }}" class="navbar-brand navbar-brand-autodark">
                    @if (getThemeFromSession() == 'light')
                        <img src="{{ asset('/storage/images/' . getSetting('PRIMARY_LOGO')) }}" width="175" loading="lazy"
                            alt="{{ __('Logo') }}" class="logo-image">
                    @else
                        <img src="{{ asset('/storage/images/' . getSetting('SECONDARY_LOGO')) }}" width="175" loading="lazy"
                            alt="{{ __('Logo') }}" class="logo-image">
                    @endif
                </a>
            </div>
            <div class="card card-md">
                <div class="card-body">
                    <h2 class="h2 text-center mb-4">{{ __('Login to your account') }}</h2>
                    <form id="loginForm" method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">{{ __('Email address or username') }}</label>
                            <input id="email" type="text" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" maxlength="50"
                                placeholder="{{ __('Your email or username') }}" autocomplete="email" required autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label class="form-label">
                                {{ __('Password') }}
                            </label>
                            <div class="input-group input-group-flat">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="{{ __('Password') }}" name="password" maxlength="50" required
                                    autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                <span class="input-group-text">
                                    <a class="link-secondary jm-toggle-password" data-bs-toggle="tooltip">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                            <path
                                                d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                                            <path
                                                d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" />
                                        </svg>
                                    </a>
                                </span>
                            </div>

                            <span class="form-label-description mt-2">
                                <a href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
                            </span>
                        </div>
                        <div class="mb-2">
                            <label class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>

                                <span class="form-check-label">{{ __('Remember me') }}</span>
                            </label>
                        </div>

                        <div class="form-footer">
                            <button type="submit" id="loginButton"
                                class="btn btn-primary w-100">{{ __('Login') }}</button>
                        </div>
                    </form>
                </div>
            </div>
            @if (getSetting('REGISTRATION') == 'enabled' && getSetting('AUTH_MODE') == 'enabled')
                <div class="text-center text-secondary mt-3">
                    {{ __("Don't have account yet?") }}
                    <a href="{{ route('register') }}" tabindex="-1">
                        {{ __('Register') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('script')
    <script src="https://www.google.com/recaptcha/api.js"></script>
@endsection
