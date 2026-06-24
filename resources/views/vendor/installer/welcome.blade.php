@extends('vendor.installer.layouts.master')
@section('container')
    <div class="card card-md">
        <div class="card-body text-center py-4 p-sm-5">
            <h1 class="mt-5">Welcome to JupiterMeet!</h1>
            <p class="text-secondary">
                This installation wizard will guide you through the setup process of the application.
            </p>
            <div class="hr-text hr-text-center hr-text-spaceless mt-5"> Installation Steps </div>

            <div class="mt-5 text-start">
                <ul class="text-secondary mt-3 ps-4">
                    <li>
                        <h4>Admin Details</h4>
                    </li>
                    <li>
                        <h4>Database Setup</h4>
                    </li>
                    <li>
                        <h4>Version Check</h4>
                    </li>
                    <li>
                        <h4>Permission Check</h4>
                    </li>
                    <li>
                        <h4>Application Installed</h4>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row align-items-center mt-3">
        <div class="col-4">
            <div class="progress progress-1">
                <div class="progress-bar" style="width: 0%;" role="progressbar" aria-valuenow="50" aria-valuemin="0"
                    aria-valuemax="100" aria-label="0% Complete">
                    <span class="visually-hidden">0% Complete</span>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="btn-list justify-content-end">
                <a href="{{ route('LaravelInstaller::admin-details') }}"><button class="btn btn-primary btn-2"
                        id="nextButton" onclick="disableButton()"> Next
                    </button></a>
            </div>
        </div>
    </div>
@stop
