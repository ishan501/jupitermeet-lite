@extends('admin.setting.index')

@section('setting-content')
    <form class="col-12 col-md-9 d-flex flex-column" action="{{ route('admin.setting.update-social-login') }}" method="post">
        @csrf
        <div class="card-body position-relative">
            <h2 class="mb-4">{{ __('Social Logins') }}</h2>

            @include('include.admin.premium-overlay', [
                'message' => __('Unlock social authentication with Google, Facebook, Twitter and more by upgrading to the pro version.'),
            ])

            <div class="blur-section">
                <div class="row mb-3">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Google') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('This will enable login with google link on login page.') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                        </label>
                        <select name="GOOGLE_SOCIAL_LOGIN"
                            class="form-control @error('GOOGLE_SOCIAL_LOGIN') is-invalid @enderror">
                            <option value="enabled" @selected(old('GOOGLE_SOCIAL_LOGIN') ? old('GOOGLE_SOCIAL_LOGIN') == 'enabled' : getSetting('GOOGLE_SOCIAL_LOGIN') == 'enabled')>
                                {{ __('On') }}
                            </option>
                            <option value="disabled" @selected(old('GOOGLE_SOCIAL_LOGIN') ? old('GOOGLE_SOCIAL_LOGIN') == 'disabled' : getSetting('GOOGLE_SOCIAL_LOGIN') == 'disabled')>
                                {{ __('Off') }}
                            </option>
                        </select>
                        @error('GOOGLE_SOCIAL_LOGIN')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Client ID') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('Client ID') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                        </label>
                        <input type="text" name="GOOGLE_CLIENT_ID"
                            class="form-control @error('GOOGLE_CLIENT_ID') is-invalid @enderror"
                            value="{{ old('GOOGLE_CLIENT_ID') ?? getSetting('GOOGLE_CLIENT_ID') }}"
                            placeholder="{{ __('Client ID') }}">
                        @error('GOOGLE_CLIENT_ID')
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
                        <label class="form-label">{{ __('Client Secret') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('Client Secret') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                        </label>
                        <div class="input-group input-group-flat">
                            <input type="password" name="GOOGLE_CLIENT_SECRET"
                                class="form-control @error('GOOGLE_CLIENT_SECRET') is-invalid @enderror"
                                value="{{ old('GOOGLE_CLIENT_SECRET') ?? getSetting('GOOGLE_CLIENT_SECRET') }}"
                                placeholder="{{ __('Client Secret') }}">
                            @error('GOOGLE_CLIENT_SECRET')
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
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label" for="i-callback-wh-url">{{ __('Callback URL') }}</label>
                        <div class="input-group">
                            <input type="text" dir="ltr" name="google_callback_url" id="i-google-cb-url"
                                class="form-control" value="{{ route('login.google.callback') }}" readonly>
                            <div class="btn btn-secondary" id="google_cb_url_copy">
                                {{ __('Copy') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row mb-3">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Facebook') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('This will enable login with facebook link on login page.') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                        </label>
                        <select name="FACEBOOK_SOCIAL_LOGIN"
                            class="form-control @error('FACEBOOK_SOCIAL_LOGIN') is-invalid @enderror">
                            <option value="enabled" @selected(old('FACEBOOK_SOCIAL_LOGIN') ? old('FACEBOOK_SOCIAL_LOGIN') == 'enabled' : getSetting('FACEBOOK_SOCIAL_LOGIN') == 'enabled')>
                                {{ __('On') }}
                            </option>
                            <option value="disabled" @selected(old('FACEBOOK_SOCIAL_LOGIN') ? old('FACEBOOK_SOCIAL_LOGIN') == 'disabled' : getSetting('FACEBOOK_SOCIAL_LOGIN') == 'disabled')>
                                {{ __('Off') }}
                            </option>
                        </select>
                        @error('FACEBOOK_SOCIAL_LOGIN')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Client ID') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('Client ID') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                        </label>
                        <input type="text" name="FACEBOOK_CLIENT_ID"
                            class="form-control @error('FACEBOOK_CLIENT_ID') is-invalid @enderror"
                            value="{{ old('FACEBOOK_CLIENT_ID') ?? getSetting('FACEBOOK_CLIENT_ID') }}"
                            placeholder="{{ __('Client ID') }}">
                        @error('FACEBOOK_CLIENT_ID')
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
                        <label class="form-label">{{ __('Client Secret') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('Client Secret') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                        </label>
                        <div class="input-group input-group-flat">
                            <input type="password" name="FACEBOOK_CLIENT_SECRET"
                                class="form-control @error('FACEBOOK_CLIENT_SECRET') is-invalid @enderror"
                                value="{{ old('FACEBOOK_CLIENT_SECRET') ?? getSetting('FACEBOOK_CLIENT_SECRET') }}"
                                placeholder="{{ __('Client Secret') }}">
                            @error('FACEBOOK_CLIENT_SECRET')
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
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Callback URL') }}</label>
                        <div class="input-group">
                            <input type="text" dir="ltr" name="facebook_callback_url" id="i-facebook-cb-url"
                                class="form-control" value="{{ route('login.facebook.callback') }}" readonly>
                            <div class="btn btn-secondary" id="facebook_cb_url_copy">
                                {{ __('Copy') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row mb-3">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Linkedin') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('This will enable login with linkedin link on login page.') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                        </label>
                        <select name="LINKEDIN_SOCIAL_LOGIN"
                            class="form-control @error('LINKEDIN_SOCIAL_LOGIN') is-invalid @enderror">
                            <option value="enabled" @selected(old('LINKEDIN_SOCIAL_LOGIN') ? old('LINKEDIN_SOCIAL_LOGIN') == 'enabled' : getSetting('LINKEDIN_SOCIAL_LOGIN') == 'enabled')>
                                {{ __('On') }}
                            </option>
                            <option value="disabled" @selected(old('LINKEDIN_SOCIAL_LOGIN') ? old('LINKEDIN_SOCIAL_LOGIN') == 'disabled' : getSetting('LINKEDIN_SOCIAL_LOGIN') == 'disabled')>
                                {{ __('Off') }}
                            </option>
                        </select>
                        @error('LINKEDIN_SOCIAL_LOGIN')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Client ID') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('Client ID') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                        </label>
                        <input type="text" name="LINKEDIN_CLIENT_ID"
                            class="form-control @error('LINKEDIN_CLIENT_ID') is-invalid @enderror"
                            value="{{ old('LINKEDIN_CLIENT_ID') ?? getSetting('LINKEDIN_CLIENT_ID') }}"
                            placeholder="{{ __('Client ID') }}">
                        @error('LINKEDIN_CLIENT_ID')
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
                        <label class="form-label">{{ __('Client Secret') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('Client Secret') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                        </label>
                        <div class="input-group input-group-flat">
                            <input type="password" name="LINKEDIN_CLIENT_SECRET"
                                class="form-control @error('LINKEDIN_CLIENT_SECRET') is-invalid @enderror"
                                value="{{ old('LINKEDIN_CLIENT_SECRET') ?? getSetting('LINKEDIN_CLIENT_SECRET') }}"
                                placeholder="{{ __('Client Secret') }}">
                            @error('LINKEDIN_CLIENT_SECRET')
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
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Callback URL') }}</label>
                        <div class="input-group">
                            <input type="text" dir="ltr" name="linkedin_callback_url" id="i-linkedin-cb-url"
                                class="form-control" value="{{ route('login.linkedin.callback') }}" readonly>
                            <div class="btn btn-secondary" id="linkedin_cb_url_copy">
                                {{ __('Copy') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row mb-3">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Twitter') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('This will enable login with twitter link on login page.') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                        </label>
                        <select name="TWITTER_SOCIAL_LOGIN"
                            class="form-control @error('TWITTER_SOCIAL_LOGIN') is-invalid @enderror">
                            <option value="enabled" @selected(old('TWITTER_SOCIAL_LOGIN') ? old('TWITTER_SOCIAL_LOGIN') == 'enabled' : getSetting('TWITTER_SOCIAL_LOGIN') == 'enabled')>
                                {{ __('On') }}
                            </option>
                            <option value="disabled" @selected(old('TWITTER_SOCIAL_LOGIN') ? old('TWITTER_SOCIAL_LOGIN') == 'disabled' : getSetting('TWITTER_SOCIAL_LOGIN') == 'disabled')>{{ __('Off') }}
                            </option>
                        </select>
                        @error('TWITTER_SOCIAL_LOGIN')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Client ID') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('Client ID') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                        </label>
                        <input type="text" name="TWITTER_CLIENT_ID"
                            class="form-control @error('TWITTER_CLIENT_ID') is-invalid @enderror"
                            value="{{ old('TWITTER_CLIENT_ID') ?? getSetting('TWITTER_CLIENT_ID') }}"
                            placeholder="{{ __('Client ID') }}">
                        @error('TWITTER_CLIENT_ID')
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
                        <label class="form-label">{{ __('Client Secret') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('Client Secret') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                        </label>
                        <div class="input-group input-group-flat">
                            <input type="password" name="TWITTER_CLIENT_SECRET"
                                class="form-control @error('TWITTER_CLIENT_SECRET') is-invalid @enderror"
                                value="{{ old('TWITTER_CLIENT_SECRET') ?? getSetting('TWITTER_CLIENT_SECRET') }}"
                                placeholder="{{ __('Client Secret') }}">
                            @error('TWITTER_CLIENT_SECRET')
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
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Callback URL') }}</label>
                        <div class="input-group">
                            <input type="text" dir="ltr" name="twitter_callback_url" id="i-twitter-cb-url"
                                class="form-control" value="{{ route('login.twitter.callback') }}" readonly>
                            <div class="btn btn-secondary" id="twitter_cb_url_copy">
                                {{ __('Copy') }}</div>
                        </div>
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
