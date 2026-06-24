@extends('user.profile.index')

@section('profile-content')
    <div class="col-12 col-md-9 d-flex flex-column card-body">
        <h2 class="mb-4">{{ __('Two Factor Authentication') }}</h2>
        <label class="showToastAbove">{{ __('Enable/Disable Two Factor Authentication') }}</label>
        <br>
        <label class="form-check form-switch mt-3">
            <input class="form-check-input toggle-user-tfa  " type="checkbox" data-id="{{ $user->id }}"
                value="{{ $user->tfa === 'active' ? 'active' : 'inactive' }}" @checked($user->tfa == 'active')>
        </label>
    </div>
@endsection
