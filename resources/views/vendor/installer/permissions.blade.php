@extends('vendor.installer.layouts.master')
@section('container')
    <div class="card card-md">
        <div class="card-body text-center py-4 p-sm-5">
            <h2 class="mb-5 text-center">Permissions Check</h2>
            <div class="list-group list-group-flush list-group-hoverable">
                @if (isset($permissions['errors']))
                    <div class="alert alert-danger">Please fix the below error and the click
                        Check Permission Again</div>
                @endif
                @foreach ($permissions['permissions'] as $permission)
                    <div class="list-group-item">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto">
                                <span>{{ $permission['folder'] }}{{ $permission['permission'] }}</span>
                            </div>
                            <div class="col-auto">
                                {!! $permission['isSet']
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
                <div class="progress-bar" style="width: 80%;" role="progressbar" aria-valuenow="50" aria-valuemin="0"
                    aria-valuemax="100" aria-label="80% Complete">
                    <span class="visually-hidden">80% Complete</span>
                </div>
            </div>
        </div>
        <div class="col">
            @if (!isset($permissions['errors']))
                <div class="btn-list justify-content-end">
                    <a href="{{ route('LaravelInstaller::database') }}" class="btn btn-primary btn-2"> Next </a>
                </div>
            @else
                <div class="btn-list justify-content-end">
                    <a class="btn btn-primary btn-2" href="javascript:window.location.href='';">
                        Check Permission Again
                    </a>
                </div>
            @endif
        </div>
    </div>
@stop
