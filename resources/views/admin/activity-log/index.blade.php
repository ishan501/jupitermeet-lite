@extends('layouts.admin')
@section('title', $pageTitle)

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
                    <!-- Page title actions -->
                    <div class="col-auto ms-auto me-3">
                        <div class="btn-list">
                            <span class="d-none d-sm-inline">
                                <a href="{{ route('export-activity-log', request()->all()) }}" class="btn hideLoader">
                                    {{ __('Export') }}
                                </a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-body">
            <div class="container-xl">
                <div class="accordion mb-3" id="activityLogSearch">
                    <div class="accordion-item">
                        <h4 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#activityLogSearchForm" aria-expanded="true">
                                {{ __('Search') }}
                            </button>
                        </h4>
                        <div id="activityLogSearchForm"
                            class="accordion-collapse collapse
                                 @if ($isFiltered) show @endif
                                 "
                            data-bs-parent="#activityLogSearch">
                            <div class="accordion-body pt-0">
                                @include('admin.activity-log.search')
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
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Event Type') }}</th>
                                    <th>{{ __('Log') }}</th>
                                    <th>{{ __('IP') }}</th>
                                    <th>{{ __('Created At') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($activityLogs as $activityLog)
                                    <tr>
                                        <td>{{ $activityLogs->firstItem() + $loop->index }}</td>
                                        <td>{{ $activityLog->causer ? $activityLog->causer->username : '-' }}</td>
                                        <td>
                                            @if ($activityLog->event == 'Logged In' || $activityLog->event == 'Logged Out' || $activityLog->event == 'Registered')
                                                {{ $activityLog->event }}
                                            @else
                                                {{ $activityLog->log_name . ' ' . $activityLog->event }}
                                                @if ($activityLog->event == 'created' && !$activityLog->causer)
                                                    {{ __('from Social Login') }}</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @foreach (json_decode($activityLog->properties, true) ?: [] as $key => $innerArray)
                                                @if (is_array($innerArray))
                                                    @foreach ($innerArray as $innerKey => $innerValue)
                                                        @if ($innerValue)
                                                            @if ($key === 'old')
                                                                {{ __('Old:') }}
                                                            @elseif ($key === 'attributes')
                                                                {{ __('Attribute:') }}
                                                            @endif
                                                            {{ $innerKey }} -
                                                            @if ($innerKey == 'content' && $activityLog->log_name == 'Email Template')
                                                                Email template content has been updated
                                                            @elseif ($innerKey == 'content' && $activityLog->log_name == 'Page')
                                                                Content
                                                            @elseif ($innerKey == 'content' && $activityLog->log_name == 'Page' && $activityLog->event == 'updated')
                                                                Page Content has been updated
                                                            @elseif ($innerKey == 'features' && $activityLog->log_name == 'Plan' && $activityLog->event == 'updated')
                                                                Plan Features has been updated
                                                            @elseif ($innerKey == 'features' && $activityLog->log_name == 'Plan' && $activityLog->event == 'created')
                                                                Plan Features has been created
                                                            @elseif ($innerKey == 'regions' && $activityLog->log_name == 'Tax rate' && $activityLog->event == 'created')
                                                                Regions have been set for this tax rate.
                                                            @elseif ($innerKey == 'regions' && $activityLog->log_name == 'Tax rate' && $activityLog->event == 'updated')
                                                                Regions have been updated for this taxrate
                                                            @elseif ($innerKey == 'coupons' && $activityLog->log_name == 'Plan' && $activityLog->event == 'updated')
                                                                Coupons have been updated
                                                            @elseif ($innerKey == 'tax_rates' && $activityLog->log_name == 'Plan' && $activityLog->event == 'updated')
                                                                Taxrates have been updated
                                                            @elseif ($innerKey == 'tax_rates' && $activityLog->log_name == 'Plan' && $activityLog->event == 'created')
                                                                Taxrates have been created
                                                            @elseif ($innerKey == 'coupons' && $activityLog->log_name == 'Plan' && $activityLog->event == 'created')
                                                                Coupons have been created
                                                            @else
                                                                <span title="{{ $innerValue }}" data-bs-toggle="tooltip"
                                                                    data-bs-placement="right">
                                                                    {{ \Illuminate\Support\Str::limit($innerValue, 20, '...') }}
                                                                </span>
                                                            @endif
                                                            <br>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>{{ $activityLog->ip }}</td>
                                        <td>
                                            <span title="{{ convertToTimezone($activityLog->created_at) }}"
                                                data-bs-toggle="tooltip" data-bs-placement="right">
                                                {{ $activityLog->created_at->diffForHumans() }}
                                            </span>
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
                    @if ($activityLogs->hasPages())
                        <div class="mt-2 ms-2 mb-2">
                            {{ $activityLogs->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('js/litepicker.js') }}"></script>
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
