@extends('admin.payment-gateway.index')

@section('payment-gateway-content')
    <form class="col-12 col-md-9 d-flex flex-column"
        method="post">
        @csrf
        <div class="card-body">
            <div class="tab-content" id="pills-tabContent">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-label" for="i-stripe">{{ __('Enabled') }}</label>
                            <select name="STRIPE" id="i-stripe" class="form-select @error('STRIPE') is-invalid @enderror">
                                @foreach ([1 => __('Yes'), 0 => __('No')] as $key => $value)
                                    <option value="{{ $key }}" @if ((old('STRIPE') !== null && old('STRIPE') == $key) || (getSetting('STRIPE') == $key && old('STRIPE') == null)) selected @endif>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('STRIPE')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-label" for="i-stripe-key">{{ __('Publishable key') }}</label>
                            <input type="text" name="STRIPE_KEY" id="i-stripe-key"
                                class="form-control @error('STRIPE_KEY') is-invalid @enderror"
                                value="{{ old('STRIPE_KEY') ?? getSetting('STRIPE_KEY') }}"
                                placeholder="{{ __('Publishable key') }}">
                            @error('STRIPE_KEY')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-label" for="i-stripe-secret">{{ __('Secret key') }}</label>
                            <div class="input-group input-group-flat">
                                <input type="password" name="STRIPE_SECRET" id="i-stripe-secret"
                                    class="form-control @error('STRIPE_SECRET') is-invalid @enderror"
                                    value="{{ old('STRIPE_SECRET') ?? getSetting('STRIPE_SECRET') }}"
                                    placeholder="{{ __('Secret key') }}">
                                @error('STRIPE_SECRET')
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
                            <label class="form-label" for="i-stripe-wh-secret">{{ __('Signing secret') }}</label>
                            <div class="input-group input-group-flat">
                                <input type="password" name="STRIPE_WH_SECRET" id="i-stripe-wh-secret"
                                    class="form-control @error('STRIPE_WH_SECRET') is-invalid @enderror"
                                    value="{{ old('STRIPE_WH_SECRET') ?? getSetting('STRIPE_WH_SECRET') }}"
                                    placeholder="{{ __('Signing secret') }}">
                                @error('STRIPE_WH_SECRET')
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

                <div class="row mt-3">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-label" for="i-stripe-wh-url">{{ __('Webhook URL') }}</label>
                            <div class="input-group">
                                <input type="text" dir="ltr" name="stripe_wh_url" id="i-stripe-wh-url"
                                    class="form-control" value="{{ route('webhooks.stripe') }}" readonly>
                                <button class="btn btn-secondary" type="button" id="webhookUrlStripeCopy">
                                    {{ __('Copy') }}
                                </button>
                            </div>
                        </div>
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
