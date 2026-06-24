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
                'message' => __('Plugin management and advanced module extensions are available in the paid version.'),
            ])
            <div class="container-xl blur-section">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th>{{ __('SR No') }}</th>
                                    <th>{{ __('Product Name') }}</th>
                                    <th>{{ __('Domain') }}</th>
                                    <th>{{ __('Token') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Created') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($plugins as $plugin)
                                    <tr>
                                        <td>{{ $plugins->firstItem() + $loop->index }}</td>
                                        <td class="text-truncate">
                                            <span title="{{ $plugin->product_name }}" data-bs-toggle="tooltip"
                                                data-bs-placement="right">
                                                {{ $plugin->product_name ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="text-truncate">
                                            <span title="{{ $plugin->domain }}" data-bs-toggle="tooltip"
                                                data-bs-placement="right">
                                                {{ $plugin->domain ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="text-truncate">
                                            <div class="d-flex align-items-center gap-2">
                                                <span title="{{ $plugin->token }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="right" class="text-truncate">
                                                    {{ $plugin->token ?? '-' }}
                                                </span>

                                                @if ($plugin->token)
                                                    <button type="button" class="btn btn-sm btn-light copy-token"
                                                        data-token="{{ $plugin->token }}" title="Copy token">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor" class="bi bi-copy"
                                                            viewBox="0 0 16 16">
                                                            <path fill-rule="evenodd"
                                                                d="M4 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 5a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1h1v1a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h1v1z" />
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                        <td scope="col">
                                            <div class="form-switch">
                                                <input class="form-check-input toggle-plugin-status" type="checkbox"
                                                    data-id="{{ $plugin->id }}"
                                                    value="{{ $plugin->status === 'active' ? 'active' : 'inactive' }}"
                                                    {{ $plugin->status === 'active' ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            <span title="{{ $plugin->created_at_custom }}" data-bs-toggle="tooltip"
                                                data-bs-placement="right">
                                                {{ $plugin->created_at->diffForHumans() }}
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn disabled">{{ __('Edit') }}</button>
                                            <button class="btn disabled">{{ __('Delete') }}</button>
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
                    @if ($plugins->hasPages())
                        <div class="mt-2 ms-2 mb-2">
                            {{ $plugins->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
                <div class="card mt-5">
                    <div class="card-header">
                        <div>
                            <h3 class="card-title mb-1">{{ __('Plugin Marketplace') }}</h3>
                            <div class="text-secondary">
                                {{ __('Explore available plugins and see what is already installed on your system.') }}
                            </div>
                        </div>

                        <div class="ms-auto">
                            <span class="badge bg-primary-lt">
                                {{ count($installedPluginIds) }} / {{ count($availablePlugins) }} {{ __('Installed') }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="list-group list-group-flush">

                            @foreach ($availablePlugins as $addonId => $addon)
                                @php
                                    $isInstalled = in_array((string) $addonId, $installedPluginIds);
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
                                                {{ $addon['description'] ?? __('No description available for this addon.') }}
                                            </div>
                                        </div>

                                        {{-- Action Button --}}
                                        <div class="col-auto">
                                            @if ($isInstalled)
                                                <button class="btn btn-secondary" disabled>
                                                    {{ __('Installed') }}
                                                </button>
                                            @else
                                                <button class="btn btn-success disabled">{{ __('Buy Now') }}</button>
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
