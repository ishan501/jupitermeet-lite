@extends('vendor.installer.layouts.master')
@section('container')
<form method="post" action="{{ route('LaravelInstaller::verify') }}" class="tabs-wrap">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="card card-md">
        @if (session()->has('message'))
            <div class="mt-2 text-center">
                <span class="badge badge-outline text-red">{{ session('message') }}</span>
            </div>
        @endif
        <div class="card-body py-4 p-sm-5">
            <div class="row">
                <div class="col-12">
                    <div class="hr-text hr-text-center hr-text-spaceless mt-5"> Admin Details </div>
                    <div class="form-group mt-5">
                        <label class="form-label">Admin Email</label>
                        <input type="text" class="form-control @error('email') is-invalid @enderror" name="email"
                            placeholder="Admin Email" value="{{ session('email') }}" required>
                        @error('email')
                            <small class="invalid-feedback">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="form-group mt-3">
                        <label class="form-label">Admin Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" placeholder="Admin Password" required>
                        @error('password')
                            <small class="invalid-feedback">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="form-group mt-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                            name="password_confirmation" placeholder="Confirm Password" required>
                        @error('password_confirmation')
                            <small class="invalid-feedback">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                </div>
                <input name="domainName" type="hidden" id="domainName" value="">
            </div>
        </div>
    </div>

    <div class="row align-items-center mt-3">
        <div class="col-4">
            <div class="progress progress-1">
                <div class="progress-bar" style="width: 20%;" role="progressbar" aria-valuenow="50" aria-valuemin="0"
                    aria-valuemax="100" aria-label="20% Complete">
                    <span class="visually-hidden">20% Complete</span>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="btn-list justify-content-end">
                <button type="submit" class="btn btn-primary btn-2" id="nextButton"> Next
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    document.querySelector('form').addEventListener('submit', function (e) {
        document.getElementById('nextButton').disabled = true;
    });

    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('domainName').value = window.location.origin;
    });
</script>

@stop