@extends('admin.setting.index')

@section('setting-content')
    <form class="col-12 col-md-9 d-flex flex-column" action="{{ route('admin.setting.update-custom-css') }}" method="post">
        @csrf
        <div class="card-body position-relative">
            <h2 class="mb-4">{{ __(key: 'Custom CSS') }}</h2>

            @include('include.admin.premium-overlay', [
                'message' => __('Unlock custom CSS styles integration by upgrading to the pro version.'),
            ])

            <div class="blur-section">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="form-label">{{ __('Custom CSS') }}
                                 <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                    title="{{ __('Add your custom CSS. Do NOT add the style tag.') }}">
                                    <path
                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                                </svg>
                            </label>
                            <textarea class="form-control @error('CUSTOM_CSS') is-invalid @enderror" name="CUSTOM_CSS"
                                placeholder="{{ __('Custom CSS') }}" rows="20">{{ old('CUSTOM_CSS') ?? getSetting('CUSTOM_CSS') }}</textarea>
                            @error('CUSTOM_CSS')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-transparent">
            <div class="btn-list justify-content-end">
                <button type="submit" name="submit" class="btn btn-primary mt-3" disabled>{{ __('Save') }}</button>
            </div>
        </div>
    </form>
@endsection
