@extends('admin.setting.index')

@section('setting-content')
    <form class="col-12 col-md-9 d-flex flex-column" action="{{ route('admin.setting.update-google-recaptcha') }}"
        method="post">
        @csrf
        <div class="card-body position-relative">
            <h2 class="mb-4">{{ __(key: 'Google ReCaptcha') }}</h2>

            @include('include.admin.premium-overlay', [
                'message' => __('Unlock Google reCAPTCHA security settings for login, registration, and checkout forms by upgrading to the pro version.'),
            ])

            <div class="blur-section">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-label">{{ __('Key') }}
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                    title="{{ __('Key') }}">
                                    <path
                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                                </svg>
                            </label>
                            <input type="text" name="GOOGLE_RECAPTCHA_KEY" maxlength="100"
                                class="form-control @error('GOOGLE_RECAPTCHA_KEY') is-invalid @enderror"
                                value="{{old('GOOGLE_RECAPTCHA_KEY') ?? getSetting('GOOGLE_RECAPTCHA_KEY') }}"
                                placeholder="{{ __('reCAPTCHA Key') }}">
                            @error('GOOGLE_RECAPTCHA_KEY')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-label">{{ __('Secret') }}
                                 <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                    title="{{ __('Secret') }}">
                                    <path
                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                                </svg> 
                            </label>
                            <div class="input-group input-group-flat">
                                <input type="password" name="GOOGLE_RECAPTCHA_SECRET" maxlength="100"
                                    class="form-control @error('GOOGLE_RECAPTCHA_SECRET') is-invalid @enderror"
                                    value="{{old('GOOGLE_RECAPTCHA_SECRET') ?? getSetting('GOOGLE_RECAPTCHA_SECRET') }}"
                                    placeholder="{{ __('reCAPTCHA Secret') }}">
                                @error('GOOGLE_RECAPTCHA_SECRET')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <span class="input-group-text">
                                    <a class="link-secondary jm-toggle-password" data-bs-toggle="tooltip">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                            <path
                                                d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                                            <path
                                                d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" />
                                        </svg>
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-label">{{ __('Login Page') }}
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                    title="{{ __('This will add a Google reCAPTCHA validation on the login page.') }}">
                                    <path
                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                                </svg>
                            </label>
                            <select name="CAPTCHA_LOGIN_PAGE"
                                class="form-control @error('CAPTCHA_LOGIN_PAGE') is-invalid @enderror">
                                <option value="enabled" @selected(old('CAPTCHA_LOGIN_PAGE') ? old('CAPTCHA_LOGIN_PAGE') == 'enabled' : getSetting('CAPTCHA_LOGIN_PAGE') == 'enabled')>
                                    {{ __('On') }}
                                </option>
                                <option value="disabled" @selected(old('CAPTCHA_LOGIN_PAGE') ? old('CAPTCHA_LOGIN_PAGE') == 'disabled' : getSetting('CAPTCHA_LOGIN_PAGE') == 'disabled')>
                                    {{ __('Off') }}
                                </option>
                            </select>
                            @error('CAPTCHA_LOGIN_PAGE')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-label">{{ __('Register Page') }}
                               <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                    title="{{ __('This will add a Google reCAPTCHA validation on the register page.') }}">
                                    <path
                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                                </svg>
                            </label>
                            <select name="CAPTCHA_REGISTER_PAGE"
                                class="form-control @error('CAPTCHA_REGISTER_PAGE') is-invalid @enderror">
                                <option value="enabled" @selected(old('CAPTCHA_REGISTER_PAGE') ? old('CAPTCHA_REGISTER_PAGE') == 'enabled' : getSetting('CAPTCHA_REGISTER_PAGE') == 'enabled')>
                                    {{ __('On') }}
                                </option>
                                <option value="disabled" @selected(old('CAPTCHA_REGISTER_PAGE') ? old('CAPTCHA_REGISTER_PAGE') == 'disabled' : getSetting('CAPTCHA_REGISTER_PAGE') == 'disabled')>
                                    {{ __('Off') }}
                                </option>
                            </select>
                            @error('CAPTCHA_REGISTER_PAGE')
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
                            <label class="form-label">{{ __('Checkout Page') }}
                               <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                    title="{{ __('This will add a Google reCAPTCHA validation on the checkout page.') }}">
                                    <path
                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                                </svg>
                            </label>
                            <select name="GOOGLE_RECAPTCHA"
                                class="form-control @error('GOOGLE_RECAPTCHA') is-invalid @enderror">
                                <option value="enabled" @selected(old('GOOGLE_RECAPTCHA') ? old('GOOGLE_RECAPTCHA') == 'enabled' : getSetting('GOOGLE_RECAPTCHA') == 'enabled')>
                                    {{ __('On') }}
                                </option>
                                <option value="disabled" @selected(old('GOOGLE_RECAPTCHA') ? old('GOOGLE_RECAPTCHA') == 'disabled' : getSetting('GOOGLE_RECAPTCHA') == 'disabled')>
                                    {{ __('Off') }}
                                </option>
                            </select>
                            @error('GOOGLE_RECAPTCHA')
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
