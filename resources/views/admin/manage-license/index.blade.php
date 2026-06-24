@extends('layouts.admin')
@section('title', $pageTitle)

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
                'message' => __('License verification and automatic update management are available in the paid version.'),
            ])
            <div class="container-xl blur-section">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <button id="verifyLicense" class="btn btn-primary disabled" disabled>{{ __('Verify License') }}</button>
                                    <button id="uninstallLicense"
                                        class="btn btn-danger disabled" disabled>{{ __('Uninstall License') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- uninstall license modal start here -->
    <div class="modal modal-blur fade" id="uninstallLicenseModal" tabindex="1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-m modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Uninstall License') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __("To proceed with license uninstallation, type 'uninstall' below") }}</p>
                    <input id="uninstallInput" class="form-control" type="text" placeholder="uninstall">
                </div>
                <div class="modal-footer">
                    <a data-bs-dismiss="modal">
                        <button id="uninstallBtn" class="btn btn-danger ms-auto" disabled>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 7l16 0" />
                                <path d="M10 11l0 6" />
                                <path d="M14 11l0 6" />
                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                            </svg>
                            {{ __('Uninstall') }}
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- uninstall license modal end here -->

@endsection
