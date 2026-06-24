@extends('layouts.app')

@section('page', $page)
@section('title', $page)

@section('content')
    @include('include.user.header')

    <div class="container-fluid">
        <div class="row align-items-center vh-100">
            <div class="col-12">
                <div class="d-flex justify-content-between flex-column text-center">
                    <div class="text-center mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-loader">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 6l0 -3" />
                            <path d="M16.25 7.75l2.15 -2.15" />
                            <path d="M18 12l3 0" />
                            <path d="M16.25 16.25l2.15 2.15" />
                            <path d="M12 18l0 3" />
                            <path d="M7.75 16.25l-2.15 2.15" />
                            <path d="M6 12l-3 0" />
                            <path d="M7.75 7.75l-2.15 -2.15" />
                        </svg>
                    </div>

                    <h5 class="text-center">{{ __('Payment Pending') }}</h5>
                    <p class="text-center text-muted">{{ __('The payment is pending approval') }}</p>
                    <div>
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">{{ __('Dashboard') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
