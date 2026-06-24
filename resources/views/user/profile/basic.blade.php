@extends('user.profile.index')

@section('profile-content')
    <form class="col-12 col-md-9 d-flex flex-column" action="{{ route('user.profile.basic.update') }}" method="post"
        enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <h2 class="mb-4">{{ __(key: 'Basic Information') }}</h2>
            @include('include.user.message')
            <div class="row showToastAbove">
                <div class="col-sm-12">
                    <input type="hidden" name="userid" id="userid" value="{{ getAuthUserInfo('id') }}" />
                    <div class="row align-items-center mb-3">
                        @if (getAuthUserInfo('avatar') && file_exists(public_path('storage/avatars/' . getAuthUserInfo('avatar'))))
                            @php
                                $avatar = getAuthUserInfo('avatar');
                            @endphp
                            <div class="col-auto">
                                <span class="avatar avatar-xl" id="avatar-preview"
                                    style="background-image: url('{{ asset('storage/avatars/' . $avatar) }}')"></span>
                            </div>
                        @else
                            <div class="col-auto">
                                <span class="avatar avatar-xl" id="avatar-preview"
                                    style="background-image: url('{{ asset('/images/blank.jpeg') }}')"></span>
                            </div>
                        @endif
                        <div class="col-auto">
                            <button id="change-avatar" type="button" class="btn" disabled>{{ __('Change avatar') }}
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-gem ms-2" viewBox="0 0 16 16">
                                <path
                                    d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
                            </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label class="form-label" for="i-name">{{ __('First Name') }}</label>
                            <input type="text" name="first_name"
                                class="form-control @error('first_name') is-invalid @enderror }}"
                                value="{{ old('first_name') ?? $user->first_name }}" minlength="3" maxlength="25" required
                                placeholder="{{ __('First Name') }}">
                            @error('first_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label class="form-label">{{ __('Last Name') }}</label>
                            <input type="text" name="last_name"
                                class="form-control @error('last_name') is-invalid @enderror"
                                value="{{ old('last_name') ?? $user->last_name }}" required minlength="3" maxlength="25"
                                placeholder="{{ __('Last Name') }}">
                            @error('last_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label class="form-label" for="i-name">{{ __('Username') }}</label>
                            <input type="text" name="username"
                                class="form-control @error('username') is-invalid @enderror }}"
                                value="{{ old('username') ?? $user->username }}" minlength="3" maxlength="20" required
                                placeholder="{{ __('Username') }}">
                            @error('username')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label class="form-label" for="i-email">{{ __('Email') }}</label>
                            <input type="text" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') ?? $user->email }}" required minlength="3" maxlength="50"
                                placeholder="{{ __('Email') }}" disabled>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label class="form-label" for="timezone">{{ __('Timezone') }}</label>

                                <select name="timezone" id="timezone"
                                    class="form-select @error('timezone') is-invalid @enderror">
                                    @foreach (config('timezones') as $timezone)
                                        <option value="{{ $timezone }}"
                                            {{ old('timezone', auth()->user()->timezone ?? 'UTC') == $timezone ? 'selected' : '' }}>
                                            {{ $timezone }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('timezone')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-transparent mt-auto">
            <div class="btn-list justify-content-end">
                <div class="row mt-3">
                    <div class="col">
                        <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
