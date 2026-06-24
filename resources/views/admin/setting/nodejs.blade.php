@extends('admin.setting.index')

@section('setting-content')
    <form class="col-12 col-md-9 d-flex flex-column" action="{{ route('admin.setting.update-nodejs') }}" method="post">
        @csrf
        <div class="card-body">
            <h2>{{ __(key: 'Signaling') }}</h2>
            <small class="text-muted">
                {{ __('Please restart the signaling server after making any changes to this section.') }}
            </small>

            <div class="row mb-3 mt-3">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('SSL Key Path') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('Path to your SSL key') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                        </label>
                        <input type="text" name="KEY_PATH" class="form-control @error('KEY_PATH') is-invalid @enderror"
                            maxlength="100" value="{{ old('KEY_PATH') ?? getSetting('KEY_PATH') }}"
                            placeholder="{{ __('Key Path') }}">
                        @error('KEY_PATH')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('SSL Certificate Path') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('Path to your SSL certificate') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                        </label>
                        <input type="text" name="CERT_PATH" class="form-control @error('CERT_PATH') is-invalid @enderror"
                            value="{{ old('CERT_PATH') ?? getSetting('CERT_PATH') }}" maxlength="100"
                            placeholder="{{ __('SSL Certificate Path') }}">
                        @error('CERT_PATH')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Port') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('Port to run NodeJS on') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                        </label>
                        <input type="number" min="10" max="9999" name="PORT" maxlength="4"
                            class="form-control @error('PORT') is-invalid @enderror"
                            value="{{ old('PORT') ?? getSetting('PORT') }}" placeholder="{{ __('Port') }}">
                        @error('PORT')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-transparent">
            <div class="btn-list justify-content-end">
                <button type="submit" name="submit" class="btn btn-primary mt-3">{{ __('Save') }}</button>
            </div>
        </div>
    </form>
@endsection
