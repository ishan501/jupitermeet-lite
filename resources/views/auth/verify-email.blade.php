@extends('layouts.auth')

@section('title', __('Verify Email'))

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
                    <h2 class="card-title text-center mb-4">{{ __('Verify Your Email') }}</h2>
                    <p class="text-secondary mb-4">
                        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                    </p>

                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success" role="alert">
                            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary w-100">
                                {{ __('Resend Verification Email') }}
                            </button>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('logout') }}" class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-link w-100">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
