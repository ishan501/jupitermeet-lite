@extends('layouts.auth')

@section('title', __('Register'))

@section('content')
    <div class="page page-center">
        <div class="container container-tight py-4">
            <div class="text-center mb-4">
                @if (getThemeFromSession() == 'light')
                    <img src="{{ asset('/storage/images/' . getSetting('PRIMARY_LOGO')) }}" width="175" loading="lazy"
                        alt="{{ __('Logo') }}" class="logo-image">
                @else
                    <img src="{{ asset('/storage/images/' . getSetting('SECONDARY_LOGO')) }}" width="175" loading="lazy"
                        alt="{{ __('Logo') }}" class="logo-image">
                @endif
            </div>
            <form class="card card-md" method="POST" action="{{ route('register') }}">
                @csrf

                <div class="card-body">
                    <h2 class="card-title text-center mb-4">{{ __('Create new account') }}</h2>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('First Name') }} </label>
                            <input type="text" value="{{ old('first_name') }}"
                                class="form-control @error('first_name') is-invalid @enderror" name="first_name"
                                placeholder="{{ __('Enter first name') }}" required autofocus maxlength="25">
                            @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('Last Name') }}</label>
                            <input type="text" value="{{ old('last_name') }}"
                                class="form-control @error('last_name') is-invalid @enderror" name="last_name"
                                placeholder="{{ __('Enter last name') }}" required maxlength="25">
                            @error('last_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Username') }}</label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" maxlength="20"
                            value="{{ old('username') }}" name="username" placeholder="{{ __('Enter username') }}"
                            required>
                        @error('username')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Email address') }}</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" maxlength="50"
                            value="{{ old('email') }}" name="email" placeholder="{{ __('Enter email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Password') }}</label>
                        <div class="input-group input-group-flat">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                minlength="6" name="password" placeholder="{{ __('Password') }}" autocomplete="off"
                                required>
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
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
                    </div>
                    <div class="mb-3">
                        <label class="form-check">
                            <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox"
                                name="terms">
                            <span class="form-check-label">{{ __('Agree the') }} <a
                                    href="{{ route('pages.show', 'terms-and-conditions') }}"
                                    tabindex="-1">{{ __('terms and policy') }}</a>.</span>
                            @error('terms')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </label>
                    </div>
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">{{ __('Register') }}</button>
                    </div>

                </div>
            </form>
            <div class="text-center text-secondary mt-3">
                {{ __('Already have account?') }} <a href="{{ route('login') }}" tabindex="-1">{{ __('Login') }}</a>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://www.google.com/recaptcha/api.js"></script>
@endsection
