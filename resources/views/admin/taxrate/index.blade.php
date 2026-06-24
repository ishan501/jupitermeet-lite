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
                    <!-- Page title actions -->
                    <div class="col-auto ms-auto me-3 blur-section">
                        <div class="btn-list">
                            <span class="d-sm-inline">
                                <a href="javascript:void(0)"
                                    class="btn btn-primary btn-5 disabled">
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
        <div class="page-body position-relative">
            @include('include.admin.premium-overlay', [
                'message' => __('Tax rate management and billing configurations are available in the paid version.'),
            ])
            <div class="container-xl blur-section">
                <div class="accordion mb-3" id="taxrateSearch">
                    <div class="accordion-item">
                        <h4 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#taxrateSearchForm" aria-expanded="true">
                                {{ __('Search') }}
                            </button>
                        </h4>
                        <div id="taxrateSearchForm"
                            class="accordion-collapse collapse @if ($isFiltered) show @endif"
                            data-bs-parent="#taxrateSearch">
                            <div class="accordion-body pt-0">
                                @include('admin.taxrate.search')
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="table-responsive border rounded">
                        <table class="table align-middle text-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('SR No') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Tax rate') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($taxrates as $taxrate)
                                    <tr>
                                        <td>{{ $taxrates->firstItem() + $loop->index }}</td>
                                        <td>{{ $taxrate->name }}</td>
                                        <td>{{ number_format($taxrate->percentage, 2, __('.'), __(',')) }}% <span
                                                class="text-muted">{{ $taxrate->type ? __('Exclusive') : __('Inclusive') }}</span>
                                        </td>
                                        <td>
                                            <div class="form-switch">
                                                <input type="checkbox" class="form-check-input toggle-taxrate-status"
                                                    data-id="{{ $taxrate->id }}"
                                                    {{ $taxrate->status == 1 ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            <a class="btn" href="{{ route('admin.taxrate.edit', $taxrate->id) }}">
                                                {{ __('Edit') }}
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
                </div>
                @if ($taxrates->hasPages())
                    <div class="mt-2 ms-2 mb-2">
                        {{ $taxrates->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    </div>
@endsection
