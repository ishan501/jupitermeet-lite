@extends('layouts.admin')
@section('title', $pageTitle)

@section('content')
    <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        @include('include.admin.breadcrumbs')
                    </div>
                </div>
            </div>
        </div>
        <div class="page-body">
            <div class="container-xl">
                <div class="card">
                    <div class="row card-body">
                        <div class="col-12 col-md-6 border-end">
                                <div class="row g-3 align-items-center">
                                    <div class="col-auto">
                                        <span class="status-indicator status-indicator-animated">
                                            <span class="status-indicator-circle"></span>
                                            <span class="status-indicator-circle"></span>
                                            <span class="status-indicator-circle"></span>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <p class="page-title"><a href="{{ $url }}"
                                        target="_blank">{{ $url }}</a></p>
                                        <div class="text-secondary">
                                            <ul class="list-inline list-inline-dots mb-0">
                                                <li class="list-inline-item"><span class="status-text"></span></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            <div class="row mt-3">
                                <div class="col-sm-12">
                                    <button id="checkSignaling" class="btn btn-primary mt-2">{{ __('Refresh') }}</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-5 d-flex flex-column ms-2">
                            <h4>{{ __('Troubleshooting') }}</h4>
                            <div class="callout callout-info">
                                <ul>
                                    <li>{{ __('Make sure, the URL is correct') }}</li>
                                    <li>{{ __('Make sure, the /server/.env file has been updated as per the documentation') }}
                                    </li>
                                    <li>{{ __('Make sure, the NodeJS service is started as per the documentation') }}</li>
                                    <li>{{ __('Make sure, the required ports are allowed in the Firewall as per the documentation') }}
                                    </li>
                                    <li>{{ __('Make sure, the SSL certificates are valid') }}</li>
                                    <li>{{ __('If you are using Cloudflare, make sure you use 8443 port') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
