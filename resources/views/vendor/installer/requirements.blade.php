@extends('vendor.installer.layouts.master')

@section('title', trans('installer_messages.requirements.title'))
@section('container')
    <div class="card card-md">
        <div class="card-body text-center py-4 p-sm-5">
            <h2 class="mb-5 text-center">Requirements Check</h2>
            <div class="list-group list-group-flush list-group-hoverable">
                @if (isset($requirements['errors']) || $phpSupportInfo['supported'] != 'success')
                    <div class="alert alert-danger">Please fix the below error and the click
                        Check Requirements Again</div>
                @endif
                <div class="list-group-item">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto">
                            <span>PHP Version >= {{ $phpSupportInfo['minimum'] }}</span>
                        </div>
                        <div class="col-auto">
                            {!! $phpSupportInfo['supported']
                                ? '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-green icon-2">
                                                                                                                                                                                                                                                                                                                                                                                            <path d="M5 12l5 5l10 -10"></path>
                                                                                                                                                                                                                                                                                                                                                                                        </svg>'
                                : '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-red icon-2">
                                                                                                                                                                                                                                                                                                                                                                                            <path d="M18 6l-12 12"></path>
                                                                                                                                                                                                                                                                                                                                                                                            <path d="M6 6l12 12"></path>
                                                                                                                                                                                                                                                                                                                                                                                        </svg>' !!}
                        </div>
                    </div>
                </div>
                @foreach ($requirements['requirements'] as $extention => $enabled)
                    <div class="list-group-item">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto">
                                <span>{{ $extention }}</span>
                            </div>
                            <div class="col-auto">
                                {!! $enabled
                                    ? '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-green icon-2">
                                                                                                                                                                                                                                                                                                                                                                                                                                            <path d="M5 12l5 5l10 -10"></path>
                                                                                                                                                                                                                                                                                                                                                                                                                                        </svg>'
                                    : '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-red icon-2">
                                                                                                                                                                                                                                                                                                                                                                                                                                            <path d="M18 6l-12 12"></path>
                                                                                                                                                                                                                                                                                                                                                                                                                                            <path d="M6 6l12 12"></path>
                                                                                                                                                                                                                                                                                                                                                                                                                                        </svg>' !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="row align-items-center mt-3">
        <div class="col-4">
            <div class="progress progress-1">
                <div class="progress-bar" style="width: 60%;" role="progressbar" aria-valuenow="50" aria-valuemin="0"
                    aria-valuemax="100" aria-label="60% Complete">
                    <span class="visually-hidden">60% Complete</span>
                </div>
            </div>
        </div>
        <div class="col">
            @if (!isset($requirements['errors']) && $phpSupportInfo['supported'] == 'success')
                <div class="btn-list justify-content-end">
                    <a href="{{ route('LaravelInstaller::permissions') }}" class="btn btn-primary btn-2"> Next </a>
                </div>
            @else
                <div class="btn-list justify-content-end">
                    <a class="btn btn-primary btn-2" href="javascript:window.location.href='';">
                        Check Requirements Again
                    </a>
                </div>
            @endif
        </div>
    </div>
@stop
