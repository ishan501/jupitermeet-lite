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
        'message' => __('Membership plan settings and configuration are available in the paid version.'),
    ])
                <div class="container-xl blur-section">
                    <div class="accordion mb-3" id="planSearch">
                        <div class="accordion-item">
                            <h4 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#planSearchForm" aria-expanded="true">
                                    {{ __('Search') }}
                                </button>
                            </h4>
                            <div id="planSearchForm"
                                class="accordion-collapse collapse @if ($isFiltered) show @endif"
                                data-bs-parent="#planSearch">
                                <div class="accordion-body pt-0">
                                    @include('admin.plan.search')
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
                                        <th>{{ __('Currency') }}</th>
                                        <th>{{ __('Monthly amount') }}</th>
                                        <th>{{ __('Yearly amount') }}</th>
                                        <th>{{ __('Description') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Popular') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($plans as $plan)
                                        <tr>
                                            <td>{{ $plans->firstItem() + $loop->index }}</td>
                                            <td><span class="text-truncate">{{ $plan->name }}</span>
                                                @if (!$plan->hasPrice())
                                                    <span class="badge bg-danger-subtle text-danger">{{ __('Default') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $plan->currency ? $plan->currency : '-' }}</td>
                                            <td>{{ $plan->amount_month ? $plan->amount_month : '-' }}</td>
                                            <td>{{ $plan->amount_year ? $plan->amount_year : '-' }}</td>
                                            <td class="text-truncate">
                                                <span title="{{ $plan->description }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="right">
                                                    {{ $plan->description }}</span>
                                            </td>
                                            <td>
                                                @if ($plan->id == 1)
                                                    -
                                                @else
                                                    <div class="form-switch">
                                                        <input type="checkbox" class="form-check-input toggle-plan-status"
                                                            data-id="{{ $plan->id }}"
                                                            {{ $plan->status == 1 ? 'checked' : '' }}>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="form-switch">
                                                    <input type="checkbox" class="form-check-input toggle-plan-popularity"
                                                        data-id="{{ $plan->id }}"
                                                        {{ $plan->popular == "true" ? 'checked' : '' }}>
                                                </div>
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
                        @if ($plans->hasPages())
                            <div class="mt-2 ms-2 mb-2">
                                {{ $plans->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
@endsection
