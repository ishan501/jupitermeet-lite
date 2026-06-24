@extends('admin.setting.index')

@section('setting-styles')
    <link rel="stylesheet" href="{{ asset('css/tom-select.bootstrap5.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/tabler-vendors.min.css') }}">
@endsection

@section('setting-content')
    <form class="col-12 col-md-9 d-flex flex-column" action="{{ route('admin.setting.update-application') }}" method="post">
        @csrf
        <div class="card-body">
            <h2 class="mb-4">{{ __(key: 'Application') }}</h2>
            <div class="row mb-3">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Auth Mode') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('This mode will turn on the register, dashboard, profile, etc modules. If this mode is off use the \'login\' URL manually to log in.') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                        </label>
                        <select name="AUTH_MODE" class="form-control @error('AUTH_MODE') is-invalid @enderror">
                            <option value="enabled" @selected(old('AUTH_MODE') ? old('AUTH_MODE') == 'enabled' : getSetting('AUTH_MODE') == 'enabled')>
                                {{ __('On') }}
                            </option>
                            <option value="disabled" @selected(old('AUTH_MODE') ? old('AUTH_MODE') == 'disabled' : getSetting('AUTH_MODE') == 'disabled')>
                                {{ __('Off') }}
                            </option>
                        </select>
                        @error('AUTH_MODE')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Payment Mode') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('This mode will turn on the payment module. An extended license is required.') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                        </label>
                        <select name="PAYMENT_MODE" class="form-control" disabled>
                            <option value="disabled" selected>
                                {{ __('Available in paid versions.') }}
                            </option>
                        </select>
                        @error('PAYMENT_MODE')
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
                        <label class="form-label">{{ __('Landing page') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('Set landing page on or off.') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-gem" viewBox="0 0 16 16">
                                <path
                                    d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
                            </svg>
                        </label>
                        <select name="LANDING_PAGE" class="form-control" disabled>
                            <option value="disabled" selected>
                                {{ __('Available in paid versions.') }}
                            </option>
                        </select>
                        @error('LANDING_PAGE')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Cookie Consent') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('If on, the system will display a cookie consent popup to the visitors.') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                        </label>
                        <select name="COOKIE_CONSENT" class="form-control @error('COOKIE_CONSENT') is-invalid @enderror">
                            <option value="enabled" @selected(old('COOKIE_CONSENT') ? old('COOKIE_CONSENT') == 'enabled' : getSetting('COOKIE_CONSENT') == 'enabled')>
                                {{ __('On') }}
                            </option>
                            <option value="disabled" @selected(old('COOKIE_CONSENT') ? old('COOKIE_CONSENT') == 'disabled' : getSetting('COOKIE_CONSENT') == 'disabled')>
                                {{ __('Off') }}
                            </option>
                        </select>
                        @error('COOKIE_CONSENT')
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
                        <label class="form-label">{{ __('Registration') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('If off, the system will disable the registration option for guests') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                        </label>
                        <select name="REGISTRATION" class="form-control @error('REGISTRATION') is-invalid @enderror">
                            <option value="enabled" @selected(old('REGISTRATION') ? old('REGISTRATION') == 'enabled' : getSetting('REGISTRATION') == 'enabled')>
                                {{ __('On') }}
                            </option>
                            <option value="disabled" @selected(old('REGISTRATION') ? old('REGISTRATION') == 'disabled' : getSetting('REGISTRATION') == 'disabled')>
                                {{ __('Off') }}
                            </option>
                        </select>
                        @error('REGISTRATION')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Verify Users') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('If on, the system will send an email to the newly registered user. He must verify it before logging in') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                        </label>
                        <select name="VERIFY_USERS" class="form-control @error('VERIFY_USERS') is-invalid @enderror">
                            <option value="enabled" @selected(old('VERIFY_USERS') ? old('VERIFY_USERS') == 'enabled' : getSetting('VERIFY_USERS') == 'enabled')>
                                {{ __('On') }}
                            </option>
                            <option value="disabled" @selected(old('VERIFY_USERS') ? old('VERIFY_USERS') == 'disabled' : getSetting('VERIFY_USERS') == 'disabled')>
                                {{ __('Off') }}
                            </option>
                        </select>
                        @error('VERIFY_USERS')
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
                        <label class="form-label">{{ __('PWA') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('If on, the system will allow users to use the application as PWA') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-gem" viewBox="0 0 16 16">
                                <path
                                    d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
                            </svg>
                        </label>
                        <select name="PWA" class="form-control" disabled>
                            <option value="disabled" selected>
                                {{ __('Available in paid versions.') }}
                            </option>
                        </select>
                        @error('PWA')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Google analytics ID') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('Google Analytics tracking ID. Set null to turn it off. It uses the format G-XXXXXXX.') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-gem" viewBox="0 0 16 16">
                                <path
                                    d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
                            </svg>
                        </label>
                        <input type="text" name="GOOGLE_ANALYTICS_ID"
                            class="form-control @error('GOOGLE_ANALYTICS_ID') is-invalid @enderror"
                            value="{{ __('Available in paid versions.') }}"
                            disabled>
                        @error('GOOGLE_ANALYTICS_ID')
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
                        <label class="form-label">{{ __('Default Theme') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('Apply a theme to your whole application.') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-gem" viewBox="0 0 16 16">
                                <path
                                    d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
                            </svg>
                        </label>
                        <select name="DEFAULT_THEME" class="form-control @error('DEFAULT_THEME') is-invalid @enderror"
                            disabled>
                            <option value="disabled" selected>
                                {{ __('Available in paid versions.') }}
                            </option>
                        </select>
                        @error('DEFAULT_THEME')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6 mb-3">
                    <label class="form-label">{{ __('Timezone') }} <svg xmlns="http://www.w3.org/2000/svg" width="16"
                            height="16" fill="currentColor" class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip"
                            data-bs-placement="right" title="{{ __('Set the default timezone for the admin panel.') }}">
                            <path
                                d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                        </svg> </label>
                    <select name="ADMIN_TIMEZONE" class="form-control @error('ADMIN_TIMEZONE') is-invalid @enderror"
                        id="select-users" autocomplete="off">
                        @foreach (config('timezones') as $timezone)
                            <option value="{{ $timezone }}" @selected(old('ADMIN_TIMEZONE') ? old('ADMIN_TIMEZONE') == $timezone : getSetting('ADMIN_TIMEZONE') == $timezone)>
                                {{ $timezone }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Social Invitation') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('Social invitation link message.') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                        </label>
                        <textarea class="form-control @error('SOCIAL_INVITATION') is-invalid @enderror"
                            name="SOCIAL_INVITATION" placeholder="{{ __('Social Invitation') }}" maxlength="255"
                            rows="3">{{ old('SOCIAL_INVITATION') ?? getSetting('SOCIAL_INVITATION') }}</textarea>
                        @error('SOCIAL_INVITATION')
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

@section('setting-script')
    <script src="{{ asset('js/tom-select.base.min.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            new TomSelect("#select-users", {
                multiple: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                }
            });
        });
    </script>
@endsection