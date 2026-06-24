@extends('layouts.auth')

@section('title', __('Reset Password'))

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
            <form class="card card-md" method="POST" action="{{ route('password.store') }}">
                @csrf
                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="card-body">
                    <h2 class="card-title text-center mb-4">{{ __('Reset Password') }}</h2>

                    <div class="mb-3">
                        <label class="form-label">{{ __('Email address') }}</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" maxlength="50"
                            value="{{ old('email', $request->email) }}" name="email" placeholder="{{ __('Enter email') }}" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('Password') }}</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" placeholder="{{ __('Password') }}" required>
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('Confirm Password') }}</label>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                            name="password_confirmation" placeholder="{{ __('Confirm Password') }}" required>
                        @error('password_confirmation')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">
                            {{ __('Reset Password') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
