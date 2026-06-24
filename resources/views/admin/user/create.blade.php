@extends('layouts.admin')
@section('title', $pageTitle)

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/tom-select.bootstrap5.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/tabler-vendors.min.css') }}">
@endsection

@section('content')
    @include('include.admin.toast')

    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    @include('include.admin.breadcrumbs', [
                        'module' => __('user'),
                    ])
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.user.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div>
                                    <label class="form-label">{{ __('First Name') }}</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                        required placeholder="{{ __('Enter First Name') }}" name="first_name" minlength="3"
                                        maxlength="25" value="{{ old('first_name') }}" autofocus>
                                    @error('first_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div>
                                    <label class="form-label">{{ __('Last Name') }}</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                        placeholder="{{ __('Enter Last Name') }}" name="last_name" required minlength="3"
                                        maxlength="25" value="{{ old('last_name') }}">
                                    @error('last_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div>
                                    <label class="form-label">{{ __('Username') }}</label>
                                    <input type="text" class="form-control @error('username') is-invalid @enderror"
                                        required placeholder="{{ __('Enter Username') }}" name="username" minlength="3"
                                        maxlength="20" value="{{ old('username') }}">
                                    @error('username')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div>
                                    <label class="form-label">{{ __('Email') }}</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        placeholder="{{ __('Enter Email') }}" name="email" required minlength="3"
                                        maxlength="50" value="{{ old('email') }}">
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3 @error('password') is-invalid @enderror">
                                <div>
                                    <label class="form-label">{{ __('Password') }}</label>
                                    <div class="input-group">
                                        <input type="password" id="passwordField"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="{{ __('Enter Password') }}" name="password" required
                                            minlength="6" maxlength="50" value="{{ old('password') }}">

                                        <span class="input-group-text toggle-password" style="cursor: pointer;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                                                <path
                                                    d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
                                            </svg>
                                        </span>

                                        <span class="input-group-text generate-password" style="cursor: pointer;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-lightbulb-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M2 6a6 6 0 1 1 10.174 4.31c-.203.196-.359.4-.453.619l-.762 1.769A.5.5 0 0 1 10.5 13h-5a.5.5 0 0 1-.46-.302l-.761-1.77a2 2 0 0 0-.453-.618A5.98 5.98 0 0 1 2 6m3 8.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1l-.224.447a1 1 0 0 1-.894.553H6.618a1 1 0 0 1-.894-.553L5.5 15a.5.5 0 0 1-.5-.5" />
                                            </svg>
                                        </span>
                                    </div>
                                    @error('password')
                                        <small class="invalid-feedback">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3 @error('country_id') is-invalid @enderror">
                                <div>
                                    <label class="form-label">{{ __('Country') }}</label>
                                    <select id="country-select"
                                        class="form-select @error('country_id') is-invalid @enderror" name="country_id"
                                        required placeholder=" {{ __('Select Option') }}">
                                        <option selected>
                                        </option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}" @selected(old('country_id') == $country->id)>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('country_id')
                                        <small class="invalid-feedback">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-md-flex align-items-center">
                                <div class="mt-3 mt-md-0">
                                    {{ __('Note: An email will be sent to the user.') }}
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-md-flex align-items-center">
                                <div class="ms-auto mt-3 mt-md-0">
                                    <a href="{{ route('admin.user') }}"><button type="button" class="btn btn-1 gap-6">
                                            {{ __('Back') }}
                                        </button></a>
                                </div>
                                <div class="ms-2 mt-3 mt-md-0">
                                    <button type="submit" class="btn btn-primary gap-6">
                                        {{ __('Save') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('js/tom-select.base.min.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            new TomSelect("#country-select", {
                multiple: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                }
            });
        });
    </script>
@endsection
