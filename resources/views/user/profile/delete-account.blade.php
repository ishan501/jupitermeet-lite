@extends('user.profile.index')

@section('profile-content')
    <div class="col-12 col-md-9 d-flex flex-column">
        <div class="card-body">
            <h2 class="mb-4 ">{{ __('Delete Account') }}</h2>
            @include('include.user.message')
            <p class="text-muted small mb-0 showToastAbove">
                {{ __('By deleting your account, all associated data will be permanently removed.') }}
            </p>
            <a class="btn btn-danger mt-5" onclick="return confirm('Are you sure you want to delete this account?')" href="{{ route('user.profile.account.delete') }}">
                {{ __('Delete') }}
            </a>
        </div>
    </div>
@endsection
