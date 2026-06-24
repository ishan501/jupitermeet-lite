@extends('layouts.app')

@section('title', $pageTitle)

@section('content')
    <link href="{{ asset('/css/cropper.min.css') }}" rel="stylesheet">
    <script src="{{ asset('/js/cropper.min.js') }}"></script>
    <div class="page jm-dashboard">
        @include('include.user.header')

        <div class="page-wrapper">
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <h2 class="page-title">{{ __('Profile') }}</h2>
                        </div>
                        <div class="col-12 col-lg-7 col-xl-7">
                            <div
                                class="jm-header-right d-flex align-items-center justify-content-end flex-column flex-md-row">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl">
                    <div class="card">
                        <div class="row g-0">
                            @include('user.profile.navbar')
                            @yield('profile-content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header" id="create-modal-add-services_header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bold js-edit-title">{{ __('Crop') }}</h2>
                    <!--end::Modal title-->
                </div>
                <!--end::Modal header-->
                <div class="modal-body py-10 px-lg-17 text-center">
                    <div class="crop-img-section">
                        <img id="previewImage" />
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button id="crop_button" type="button" class="btn btn-primary">{{ __('Crop & Save') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('/js/profile.js') }}"></script>
@endsection
