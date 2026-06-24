@extends('layouts.admin')

@section('title', $pageTitle)
@section('style')
    <style>
        .card .nav.flex-column>li {
            border-bottom: unset;
        }
    </style>
@endsection

@section('content')
    @include('include.admin.toast')

    <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col blur-section">
                        @include('include.admin.breadcrumbs')
                    </div>
                </div>
            </div>
        </div>
        <div class="page-body position-relative">
            @include('include.admin.premium-overlay', [
                'message' => __('Payment gateway configuration and online transactions are available in the paid version.'),
            ])
            <div class="container-xl blur-section">
                <div class="card">
                    <div class="row g-0">
                        @include('admin.payment-gateway.navbar')
                        @yield('payment-gateway-content')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
