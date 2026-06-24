@extends('layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('include.user.toast')
    <div class="page jm-dashboard">
        @include('include.user.header')
        <div class="page-wrapper">
            <!-- Page body -->
            <div class="page-body mb-0">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <div class="card">
                                <form method="POST" class="card card-md" action="{{ route('tfa.post') }}">
                                    @csrf
                                    <div class="card-body">
                                        <h2 class="card-title card-title-lg text-center mb-4">
                                            {{ __('Authenticate Your Account') }}</h2>
                                        <p class="my-4 text-center">
                                            {{ __('Please confirm your account by entering the authorization code sent to') }}
                                            {{ substr(auth()->user()->email, 0, 5) . '******' . substr(auth()->user()->email, -2) }}
                                        </p>
                                        <div class="my-5">
                                            <div class="row g-4">
                                                <div class="col">
                                                    <div class="row g-2">
                                                        <div class="col">
                                                            <input type="number" class="form-control" name="code"
                                                                placeholder="{{ __('Enter Code') }}" required
                                                                maxlength="4" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-footer">
                                            <div class="btn-list flex-nowrap justify-content-center">
                                                <button type="submit" class="btn btn-primary">
                                                    {{ __('Verify') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="text-center text-secondary mt-3">
                                {{ __('It may take a minute to receive your code. Haven`t received it?') }} <br>
                                <a href="{{ route('tfa.resend') }}">{{ __('Resend a new code.') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
