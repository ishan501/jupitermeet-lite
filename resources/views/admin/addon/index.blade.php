@extends('layouts.admin')
@section('title', $pageTitle)

@section('content')
    @include('include.admin.toast')

    <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col blur-section">
                        @include('include.admin.breadcrumbs')
                    </div>
                    <div class="col-auto ms-auto me-3 blur-section">
                        <div class="btn-list">
                            <span class="d-sm-inline">
                                <a href="javascript:void(0)" class="btn btn-primary btn-5 disabled">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-0" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 5l0 14" />
                                        <path d="M5 12l14 0" />
                                    </svg>
                                    <span class="d-none d-sm-inline-block">{{ __('Register') }}</span>
                                </a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-body position-relative">
            @include('include.admin.premium-overlay', [
                'message' => __('Addon management and external integrations are available in the paid version.'),
            ])
            <div class="container-xl blur-section">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th>{{ __('SR No') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Created') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($addons as $addon)
                                    <tr>
                                        <td>{{ $addons->firstItem() + $loop->index }}</td>
                                        <td class="text-truncate">
                                            <span title="{{ $addon->addon_name }}" data-bs-toggle="tooltip"
                                                data-bs-placement="right">
                                                {{ $addon->addon_name ?? '-' }}
                                            </span>
                                        </td>
                                        <td scope="col">
                                            <div class="form-switch">
                                                <input class="form-check-input toggle-addon-status" type="checkbox"
                                                    data-id="{{ $addon->id }}"
                                                    value="{{ $addon->status === 'active' ? 'active' : 'inactive' }}"
                                                    {{ $addon->status === 'active' ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            <span title="{{ $addon->created_at_custom }}" data-bs-toggle="tooltip"
                                                data-bs-placement="right">
                                                {{ $addon->created_at->diffForHumans() }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href = "{{ route('admin.addon.destroy', $addon->id) }}" class="btn"
                                                onclick="return confirm('Are you sure you want to delete this Addon?')">
                                                {{ __('Delete') }}
                                            </a>
                                            <a href = "{{ route('admin.addon.setting', $addon->id) }}" class="btn">
                                                {{ __('Settings') }}
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
                    @if ($addons->hasPages())
                        <div class="mt-2 ms-2 mb-2">
                            {{ $addons->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
                <div class="card mt-5">
                    <div class="card-header">
                        <div>
                            <h3 class="card-title mb-1">{{ __('Addons Marketplace') }}</h3>
                            <div class="text-secondary">
                                {{ __('Explore available addons and see what is already installed on your system.') }}
                            </div>
                        </div>

                        <div class="ms-auto">
                            <span class="badge bg-primary-lt">
                                {{ count($installedAddonIds) }} / {{ count($availableAddons) }} {{ __('Installed') }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="list-group list-group-flush">

                            @foreach ($availableAddons as $addonId => $addon)
                                @php
                                    $isInstalled = in_array((string) $addonId, $installedAddonIds);
                                @endphp

                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        {{-- Image --}}
                                        <div class="col-auto">
                                            <span class="avatar avatar-xl"
                                                style="background-image: url('{{ asset('images/' . $addon['image']) }}')">
                                            </span>
                                        </div>

                                        {{-- Name + Description --}}
                                        <div class="col">
                                            <div class="d-flex align-items-center mb-1">
                                                <h3 class="mb-0 me-2">
                                                    {{ $addon['name'] }}
                                                </h3>

                                                @if ($isInstalled)
                                                    <span class="badge bg-success-lt">
                                                        {{ __('Installed') }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-azure-lt">
                                                        {{ __('Available') }}
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="text-secondary">
                                                {{ $addon['description']  }}
                                            </div>
                                        </div>

                                        {{-- Action Button --}}
                                        <div class="col-auto">
                                            @if ($isInstalled)
                                                <button class="btn btn-secondary" disabled>
                                                    {{ __('Installed') }}
                                                </button>
                                            @else
                                                <a href="{{ $addon['buy_url'] ?? '#' }}" target="_blank"
                                                    class="btn btn-success">
                                                    {{ __('Buy Now') }}
                                                </a>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
