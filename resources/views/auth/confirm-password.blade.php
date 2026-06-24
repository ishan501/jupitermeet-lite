@extends('layouts.auth')

@section('title', __('Confirm Password'))

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
            
            <form class="card card-md" method="POST" action="{{ route('password.confirm') }}">
                @csrf
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">{{ __('Confirm Password') }}</h2>
                    <p class="text-secondary mb-4">
                        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
                    </p>

                    <div class="mb-3">
                        <label class="form-label">{{ __('Password') }}</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" placeholder="{{ __('Password') }}" required autocomplete="current-password">
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">
                            {{ __('Confirm') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
