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
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="40"  height="40"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon-tabler-xbox-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 21a9 9 0 0 0 9 -9a9 9 0 0 0 -9 -9a9 9 0 0 0 -9 9a9 9 0 0 0 9 9z" /><path d="M9 8l6 8" /><path d="M15 8l-6 8" /></svg>
                </div>

                <h5 class="text-center">{{ __('Payment Cancelled') }}</h5>
                <p class="text-center text-muted">{{ __('The payment was cancelled') }}</p>
                <div>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">{{ __('Dashboard') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection