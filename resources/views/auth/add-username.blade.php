@extends('layouts.auth')

@section('title', __('Set Username'))

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
                    <form method="POST" action="{{ route('username.add.verify') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">{{ __('Set Username') }}</label>
                            <input type="text" name="username" value="{{ old('username') }}"
                                class="form-control @error('username') is-invalid @enderror" placeholder="Enter username">
                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary w-100">{{ __('Submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
