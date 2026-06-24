@extends('admin.setting.index')

@section('setting-content')
    <form class="col-12 col-md-9 d-flex flex-column" action="{{ route('admin.setting.update-basic') }}"
        enctype="multipart/form-data" method="post">
        @csrf
        <div class="card-body">
            <h2 class="mb-4">{{ __(key: 'Basic') }}</h2>
            <div class="row mb-3">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Application Name') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('Application Name will be visible in the entire application.') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                        </label>
                        <input type="text" name="APPLICATION_NAME"
                            class="form-control @error('APPLICATION_NAME') is-invalid @enderror" required minlength="3"
                            maxlength="25" value="{{ old('APPLICATION_NAME') ?? getSetting('APPLICATION_NAME') }}"
                            placeholder="{{ __('Application Name') }}">
                        @error('APPLICATION_NAME')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Primary Color') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('Set the primary color for the front-end.') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                        </label>
                        <input type="color" name="PRIMARY_COLOR" required
                            class="form-control @error('PRIMARY_COLOR') is-invalid @enderror"
                            value="{{ old('PRIMARY_COLOR') ?? getSetting('PRIMARY_COLOR') }}">
                        @error('PRIMARY_COLOR')
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
                        <label class="form-label">{{ __('Primary Logo') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('This will be the main logo. Only PNG is supported. The maximum allowed size is 2 MB.') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                        </label>
                        <input type="file" name="PRIMARY_LOGO" accept=".png"
                            class="form-control @error('PRIMARY_LOGO') is-invalid @enderror"
                            value="{{ old('PRIMARY_LOGO') ?? getSetting('PRIMARY_LOGO') }}">
                        @error('PRIMARY_LOGO')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <small class="form-text text-muted">
                            {{ __('Note : Upload the colored version of your logo.') }}
                        </small>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Secondary Logo') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('This will be the secondary logo. Only PNG is supported. The maximum allowed size is 2 MB.') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                        </label>
                        <input type="file" name="SECONDARY_LOGO" accept=".png"
                            class="form-control @error('SECONDARY_LOGO') is-invalid @enderror"
                            value="{{ old('SECONDARY_LOGO') ?? getSetting('SECONDARY_LOGO') }}">
                        @error('SECONDARY_LOGO')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <small class="form-text text-muted">
                            {{ __('Note : Upload the white version of your logo.') }}
                        </small>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Favicon Icon') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('This will be the favicon. Only PNG is supported. The maximum allowed size is 2 MB.') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                        </label>
                        <input type="file" name="FAVICON" accept=".png"
                            class="form-control @error('FAVICON') is-invalid @enderror"
                            value="{{ old('FAVICON') ?? getSetting('FAVICON') }}">
                        @error('FAVICON')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <small class="form-text text-muted">
                            {{ __('Note : Use a favicon icon with dimensions of at least 144x144 for PWA compatibility.') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-transparent mt-auto">
            <div class="btn-list justify-content-end">
                <button type="submit" name="submit" class="btn btn-primary btn-2">{{ __('Save') }}</button>
            </div>
        </div>
    </form>
@endsection
