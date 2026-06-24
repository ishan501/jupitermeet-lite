@extends('layouts.admin')
@section('title', $pageTitle)

@section('styles')
    @yield('setting-styles')
@endsection

@section('content')
    @include('include.admin.toast')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    @include('include.admin.breadcrumbs')
                </div>
                <!-- Page title actions -->
                <div class="col-auto ms-auto me-3">
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="row g-0">
                    @include('admin.setting.navbar')
                    @yield('setting-content')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @yield('setting-script')
@endsection
