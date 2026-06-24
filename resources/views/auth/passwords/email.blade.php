@extends('layouts.auth')

@section('title', __('Reset Password'))

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
            <form class="card card-md" method="POST" action="{{ route('password.email') }}">
                <div class="card-body">
                    <h2 class="h2 text-center mb-4">{{ __('Reset Password') }}</h2>

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p class="text-secondary mb-4">
                        {{ __('Please enter your email address. We\'ll send you a link to reset your password.') }}</p>
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">{{ __('Email address') }}</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                            value="{{ old('email') }}" maxlength="50" placeholder="{{ __('Your email') }}"
                            autocomplete="email" required autofocus>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-footer">
                        <button type="submit" id="loginButton"
                            class="btn btn-primary btn-4 w-100">{{ __('Send Reset Password Link') }}</button>
                    </div>
                </div>
            </form>
            <div class="text-center text-secondary mt-3"> <a
                    href="{{ route('login') }}">{{ __('Return to the sign-in page') }}</a>.
            </div>
        </div>
    </div>
@endsection
