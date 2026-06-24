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
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="40"  height="40"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class=" icon-tabler-circle-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 12l2 2l4 -4" /></svg>
                </div>

                <h5 class="text-center">{{ __('Payment completed') }}</h5>
                <p class="text-center text-muted">{{ __('The payment was successful') }}</p>
                <div>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">{{ __('Dashboard') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection