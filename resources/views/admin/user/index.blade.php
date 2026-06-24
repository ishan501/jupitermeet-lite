@extends('layouts.admin')
@section('title', $pageTitle)
@section('styles')
    <link rel="stylesheet" href="{{ asset('/css/tabler-flags.min.css') }}">
@endsection

@section('content')
    @include('include.admin.toast')

    <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        @include('include.admin.breadcrumbs')
                    </div>
                    <div class="col-auto ms-auto me-3">
                        <div class="btn-list">
                            <span class="d-sm-inline">
                                <a href="{{ route('export-user', request()->all()) }}" class="btn hideLoader">
                                    {{ __('Export') }}
                                </a>
                            </span>
                            <span class="d-sm-inline">
                                <a href="{{ route('admin.user.create') }}" class="btn btn-primary btn-5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-0" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 5l0 14" />
                                        <path d="M5 12l14 0" />
                                    </svg>
                                    <span class="d-none d-sm-inline-block">{{ __('Create New') }}</span>
                                </a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-body">
            <div class="container-xl">
                <div class="accordion mb-3" id="userSearch">
                    <div class="accordion-item">
                        <h4 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#userSearchForm" aria-expanded="true">
                                {{ __('Search') }}
                            </button>
                        </h4>
                        <div id="userSearchForm"
                            class="accordion-collapse collapse @if ($isFiltered) show @endif"
                            data-bs-parent="#userSearch">
                            <div class="accordion-body pt-0">
                                @include('admin.user.search')
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th>{{ __('SR No') }}</th>
                                    <th>{{ __('Avatar') }}</th>
                                    <th>{{ __('First Name') }}</th>
                                    <th>{{ __('Last Name') }}</th>
                                    <th>{{ __('Country') }}</th>
                                    <th>{{ __('Username') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Activity') }}</th>
                                    <th>{{ __('Plan') }}</th>
                                    <th>{{ __('Source') }}</th>
                                    <th>{{ __('Created') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td>{{ $users->firstItem() + $loop->index }}</td>
                                        <td>
                                            @if ($user->avatar)
                                                <span class="avatar"
                                                    style="background-image: url('{{ asset('storage/avatars/' . $user->avatar) }}')">
                                                </span>
                                            @else
                                                <span class="avatar"
                                                    style="background-image: url('{{ asset('/images/blank.jpeg') }}')"></span>
                                            @endif
                                        </td>
                                        <td class="text-truncate">
                                            <span title="{{ $user->first_name }}" data-bs-toggle="tooltip"
                                                data-bs-placement="right">
                                                {{ $user->first_name ?? '-' }}</span>
                                        </td>
                                        <td class="text-truncate">
                                            <span title="{{ $user->last_name }}" data-bs-toggle="tooltip"
                                                data-bs-placement="right">
                                                {{ $user->last_name ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="text-truncate">
                                            @if ($user->country)
                                                <span class="flag flag-xs flag-country-{{ $user->country->code }} me-2"
                                                    data-bs-toggle="tooltip" data-bs-placement="right"
                                                    title="{{ $user->country->name }}"></span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-truncate"> <span title="{{ $user->username }}"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="right">{{ $user->username }}</span></td>
                                        <td class="text-truncate">
                                            <span title="{{ $user->email }}" data-bs-toggle="tooltip"
                                                data-bs-placement="right">{{ $user->email }}</span>
                                        </td>
                                        <td>
                                            @if ($user->last_active)
                                                @if (now()->subMinutes(2)->lt($user->last_active))
                                                    <span class="badge bg-success-lt text-white" data-bs-toggle="tooltip"
                                                        data-bs-placement="right"
                                                        title="{{ $user->last_active->diffForHumans() }}">
                                                        {{ __('Online') }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger-lt text-white" data-bs-toggle="tooltip"
                                                        data-bs-placement="right"
                                                        title="{{ $user->last_active->diffForHumans() }}">
                                                        {{ __('Offline') }}
                                                    </span>
                                                @endif
                                            @else
                                                {{ __('-') }}
                                            @endif

                                        </td>
                                        <td>
                                            <select class="form-control dropdown-toggle assignPlanDropdown">
                                                @foreach ($plans as $plan)
                                                    <option value="{{ $plan->id }}|{{ $user->id }}"
                                                        {{ $plan->id == $user->plan_id ? 'selected' : '' }}>
                                                        {{ $plan->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        @if ($user->facebook_id)
                                            <td><span class="badge">Facebook</span></td>
                                        @elseif ($user->twitter_id)
                                            <td><span class="badge">Twitter</span></td>
                                        @elseif ($user->google_id)
                                            <td><span class="badge">Google</span></td>
                                        @elseif ($user->linkedin_id)
                                            <td><span class="badge">LinkedIn</span></td>
                                        @else
                                            <td><span class="badge">{{ __('Register') }}</span></td>
                                        @endif

                                        <td>
                                            <span title="{{ $user->created_at_custom }}" data-bs-toggle="tooltip"
                                                data-bs-placement="right">
                                                {{ $user->created_at->diffForHumans() }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="form-switch">
                                                <input class="form-check-input toggle-user-status" type="checkbox"
                                                    data-id="{{ $user->id }}"
                                                    value="{{ $user->status === 'active' ? 'active' : 'inactive' }}"
                                                    {{ $user->status === 'active' ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            <a class="btn" href = "{{ route('admin.user.destroy', $user->id) }}"
                                                onclick="return confirm('Are you sure you want to delete this User?')"
                                                title="{{ __('Delete') }}">
                                                {{ __('Delete') }}
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td>{{ __('No Records Found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($users->hasPages())
                        <div class="mt-2 ms-2 mb-2">
                            {{ $users->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('/js/litepicker.js') }}"></script>
    <script>
        const picker = new Litepicker({
            element: document.getElementById('litepicker'),
            singleMode: false,
            format: 'YYYY/MM/DD',
            autoApply: true,
        });

        $(document).ready(function() {
            $("#clearDate").click(function() {
                $("#litepicker").val("");
            });
        });
    </script>
@endsection
