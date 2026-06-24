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
                'message' => __('Automatic update management is available in the paid version.'),
            ])
            <div class="container-xl blur-section">
                <div class="card">
                    <div class="row card-body">
                        <div class="col-12 col-md-4 border-end">
                            <div class="col-md-10">
                                <div class="row mb-2">
                                    <div class="form-group">
                                        <span>{{ __('Current version') }}: </span>
                                        <label>{{ getSetting('VERSION') }}</label>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="form-group">
                                        <span>{{ __('Note: Once the update is downloaded, it is recommended to restart the signaling server from the terminal. Refer to the Manage Processes section from the installation manual.') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <button id="checkForUpdate"
                                            class="btn btn-primary hideLoader disabled" disabled>{{ __('Check for update') }}</button>
                                        <button id="downloadUpdate" class="btn btn-success disabled"
                                            hidden disabled>{{ __('Download') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-7 d-flex flex-column ms-2">
                            <div class="form-group">
                                <div class="callout callout-info">
                                    <h5>{{ __('Changelog') }}</h5>

                                    <pre id="changelog">-</pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection