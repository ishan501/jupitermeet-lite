@extends('layouts.auth')

@section('title', __('Forgot Password'))

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
            <form class="card card-md" method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">{{ __('Forgot Password') }}</h2>
                    <p class="text-secondary mb-4">
                        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                    </p>

                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">{{ __('Email address') }}</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" maxlength="50"
                            value="{{ old('email') }}" name="email" placeholder="{{ __('Enter email') }}" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">
                            {{ __('Email Password Reset Link') }}
                        </button>
                    </div>
                </div>
            </form>
            <div class="text-center text-secondary mt-3">
                {{ __('Forget it,') }} <a href="{{ route('login') }}">{{ __('send me back') }}</a> {{ __('to the sign in screen.') }}
            </div>
        </div>
    </div>
@endsection
