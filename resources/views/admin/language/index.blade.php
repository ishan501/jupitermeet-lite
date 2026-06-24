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
                                <a href="javascript:void(0)" class="btn disabled">
                                    {{ __('Download Sample') }}
                                </a>
                            </span>
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
                'message' => __('Multiple language support and customization are available in the paid version.'),
            ])
            <div class="blur-section">
                <div class="container-xl">
                    <div class="accordion mb-3" id="languageSearch">
                        <div class="accordion-item">
                            <h4 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#languageSearchForm" aria-expanded="true">
                                    {{ __('Search') }}
                                </button>
                            </h4>
                            <div id="languageSearchForm"
                                class="accordion-collapse collapse @if ($isFiltered) show @endif"
                                data-bs-parent="#languageSearch">
                                <div class="accordion-body pt-0">
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
                                        <th>{{ __('Code') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Direction') }}</th>
                                        <th>{{ __('Default') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($languages as $language)
                                        <tr>
                                            <td>{{ $languages->firstItem() + $loop->index }}</td>
                                            <td>{{ $language->code }}</td>
                                            <td>{{ $language->name }}</td>
                                            <td>{{ strtoupper($language->direction) }}</td>
                                            <td>
                                                @if ($language->default == 'yes')
                                                    <span
                                                        class="badge bg-success-subtle text-success">{{ __('Yes') }}</span>
                                                @else
                                                    <span class="badge bg-danger-subtle text-danger">{{ __('No') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($language->status == 'active')
                                                    <span
                                                        class="badge bg-success-subtle text-success">{{ __('Active') }}</span>
                                                @else
                                                    <span
                                                        class="badge bg-danger-subtle text-danger">{{ __('Inactive') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="javascript:void(0)" class="btn disabled">
                                                    {{ __('Edit') }}
                                                </a>
                                                <a href="javascript:void(0)" class="btn disabled">
                                                    {{ __('Delete') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7">{{ __('No Records Found') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if ($languages->hasPages())
                            <div class="mt-2 ms-2 mb-2">
                                {{ $languages->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
