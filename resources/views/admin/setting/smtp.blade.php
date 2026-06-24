@extends('admin.setting.index')

@section('setting-content')
    <form class="col-12 col-md-9 d-flex flex-column" action="{{ route('admin.setting.update-smtp') }}" method="post">
        @csrf
        <div class="card-body">
            <h2>{{ __(key: 'SMTP') }}</h2>
            <small class="text-muted">
                {{ __("Please restart the jobs using command 'pm2 restart jobs' after making any changes to this section.") }}
            </small>

            <div class="row mb-3 mt-3">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Mail Mailer') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('Mail Mailer. ex: smtp') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                        </label>
                        <input type="text" name="MAIL_MAILER" maxlength="100"
                            class="form-control @error('MAIL_MAILER') is-invalid @enderror"
                            value="{{ old('MAIL_MAILER') ?? getSetting('MAIL_MAILER') }}"
                            placeholder="{{ __('Mail Mailer') }}">
                        @error('MAIL_MAILER')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Mail Host') }}
                           <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('Mail Host. ex: smtp.gmail.com') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                        </label>
                        <input type="text" name="MAIL_HOST" class="form-control @error('MAIL_HOST') is-invalid @enderror"
                            maxlength="100" value="{{ old('MAIL_HOST') ?? getSetting('MAIL_HOST') }}"
                            placeholder="{{ __('Mail Host') }}">
                        @error('MAIL_HOST')
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
                        <label class="form-label">{{ __('Mail Port') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('Mail Port. ex: 465') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                        </label>
                        <input type="number" name="MAIL_PORT" class="form-control @error('MAIL_PORT') is-invalid @enderror"
                            maxlength="100" value="{{ old('MAIL_PORT') ?? getSetting('MAIL_PORT') }}"
                            placeholder="{{ __('Mail Port') }}">
                        @error('MAIL_PORT')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Mail Username') }}
                           <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('Mail Username. ex. admin@yourdomain.in') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                        </label>
                        <input type="text" name="MAIL_USERNAME"
                            class="form-control @error('MAIL_USERNAME') is-invalid @enderror" maxlength="100"
                            value="{{ old('MAIL_USERNAME') ?? getSetting('MAIL_USERNAME') }}"
                            placeholder="{{ __('Mail Username') }}">
                        @error('MAIL_USERNAME')
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
                        <label class="form-label">{{ __('Mail Password') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('Mail Password') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                        </label>
                        
                        <div class="input-group input-group-flat">
                            <input type="password" name="MAIL_PASSWORD"
                                class="form-control @error('MAIL_PASSWORD') is-invalid @enderror"
                                value="{{ old('MAIL_PASSWORD') ?? getSetting('MAIL_PASSWORD') }}"
                                placeholder="{{ __('Mail Password') }}">
                            @error('MAIL_PASSWORD')
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
                        <label class="form-label">{{ __('Mail Encryption') }}
                             <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('Mail Encryption. ex: ssl') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                        </label>

                        <select class="form-control{{ $errors->has('MAIL_ENCRYPTION') ? ' is-invalid' : '' }}"
                            name="MAIL_ENCRYPTION">
                            <option value="">{{ __('Select') }}</option>
                            <option value="ssl" @if (getSetting('MAIL_ENCRYPTION') == 'ssl') selected @endif>
                                {{ __('SSL') }}
                            </option>
                            <option value="tls" @if (getSetting('MAIL_ENCRYPTION') == 'tls') selected @endif>
                                {{ __('TLS') }}
                            </option>
                            <option value="starttls" @if (getSetting('MAIL_ENCRYPTION') == 'starttls') selected @endif>
                                {{ __('STARTTLS') }}
                            </option>
                        </select>
                        @error('MAIL_ENCRYPTION')
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
                        <label class="form-label">{{ __('Mail From Address') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="{{ __('Mail From Address. ex: admin@yourdomain.in') }}">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                        </label>
                        <input type="text" name="MAIL_FROM_ADDRESS" maxlength="100"
                            class="form-control @error('MAIL_FROM_ADDRESS') is-invalid @enderror"
                            value="{{ old('MAIL_FROM_ADDRESS') ?? getSetting('MAIL_FROM_ADDRESS') }}"
                            placeholder="{{ __('Mail From Address') }}">
                        @error('MAIL_FROM_ADDRESS')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="bg-transparent">
                <div class="btn-list justify-content-end">
                    <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
                </div>
            </div>
    </form>

    <hr>

    <div class="test-smtp">
        <h4>{{ __('Test SMTP') }}</h4>
        <p>{{ __('Save the above form and test the SMTP details') }}</p>

        <div id="success" class="alert alert-success d-flex align-items-center d-none" role="alert">

            <i class="fa fa-check-circle mr-3" style="font-size: 24px;"></i>
            <div>
                {{ __('An email has been sent successfully') }}
            </div>
        </div>

        <div id="error" class="alert alert-danger d-flex align-items-center d-none" role="alert">
            <i class="fa fa-exclamation-triangle mr-3" style="font-size: 24px;"></i>
            <div class="log"></div>
        </div>

        <form id="testSmtp">
            <div class="row mb-3">
                <div class="col-7 col-md-6">
                    <input id="smtpEmail" type="text" class="form-control"
                        placeholder="{{ __('Enter an email address') }}" maxlength="100" required />
                </div>
                <div class="col-5 col-md-6">
                    <button id="testSmtpButton" type="submit"
                        class="btn btn-warning ">{{ __('Send Test Email') }}</button>
                </div>
            </div>
        </form>
    </div>
@endsection
