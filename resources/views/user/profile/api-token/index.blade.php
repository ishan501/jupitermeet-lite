@extends('user.profile.index')

@section('profile-content')
    <div class="col-12 col-md-9 d-flex flex-column card-body position-relative">
        <h2 class="">{{ __('API Tokens') }}</h2>
        @include('include.user.message')

        @include('include.admin.premium-overlay', [
            'message' => __('API access and token management are available in the paid version.'),
        ])

        <div class="blur-section">
            <div class="row mb-3 ">
                <div class="col-sm-12">
                    <button class="btn btn-primary disabled" title="{{ __('Create API Token') }}">{{ __('Create') }}</button>
                </div>
            </div>
            @if (count($apiTokens))
                <table class="table table-bordered table-striped table-hover showToastAbove">
                    <thead>
                        <tr>
                            <th>{{ __('SR No') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('API Token') }}</th>
                            <th>{{ __('Created') }}</th>
                            <th>{{ __('Last Accessed') }}</th>
                            <th>{{ __('Delete') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($apiTokens as $apiToken)
                            <tr>
                                <td>{{ $apiTokens->firstItem() + $loop->index }}
                                </td>
                                <td>{{ $apiToken->name }}</td>
                                <td>
                                    <span class="font-monospace">
                                        {{ str_repeat('*', 10) }}{{ substr($apiToken->token, -4) }}
                                    </span>

                                    <button class="btn btn-sm disabled">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-copy">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M7 7m0 2.667a2.667 2.667 0 0 1 2.667 -2.667h8.666a2.667 2.667 0 0 1 2.667 2.667v8.666a2.667 2.667 0 0 1 -2.667 2.667h-8.666a2.667 2.667 0 0 1 -2.667 -2.667z" />
                                            <path
                                                d="M4.012 16.737a2.005 2.005 0 0 1 -1.012 -1.737v-10c0 -1.1 .9 -2 2 -2h10c.75 0 1.158 .385 1.5 1" />
                                        </svg>
                                    </button>
                                </td>
                                <td>{{ $apiToken->created_at->diffForHumans() }}</td>
                                <td>{{ $apiToken->last_accessed ? $apiToken->last_accessed->diffForHumans() : '-' }}</td>
                                <td>
                                    <button class="btn btn btn-danger disabled">
                                        {{ __('Delete') }}
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>{{ __('Your API Token will appear here') }}</p>
            @endif
        </div>
    </div>
@endsection
