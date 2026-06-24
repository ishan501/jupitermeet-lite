@extends('vendor.installer.layouts.master')

@section('title', trans('installer_messages.final.title'))
@section('container')
    <div class="card card-md">
        <div class="card-body text-center py-4 p-sm-5">
            <p class="paragraph" style="text-align: center;">Application has been successfully installed.</p>
        </div>
    </div>
    <div class="row align-items-center mt-3">
        <div class="col-4">
            <div class="progress progress-1">
                <div class="progress-bar" style="width: 100%;" role="progressbar" aria-valuenow="50" aria-valuemin="0"
                    aria-valuemax="100" aria-label="100% Complete">
                    <span class="visually-hidden">100% Complete</span>
                </div>
            </div>
        </div>
        <div class="col">
            @if (!isset($permissions['errors']))
                <div class="btn-list justify-content-end">
                    <a href="{{ url('/') }}" class="btn btn-primary btn-2">
                        {{ trans('installer_messages.final.exit') }} </a>
                </div>
            @endif
        </div>
    </div>
@stop
