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
                                <a href="{{ route('export-meeting', request()->all()) }}" class="btn hideLoader">
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
                <div class="accordion mb-3" id="meetingSearch">
                    <div class="accordion-item">
                        <h4 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#meetingSearchForm" aria-expanded="true">
                                {{ __('Search') }}
                            </button>
                        </h4>
                        <div id="meetingSearchForm"
                            class="accordion-collapse collapse @if ($isFiltered) show @endif"
                            data-bs-parent="#meetingSearch">
                            <div class="accordion-body pt-0">
                                @include('admin.meeting.search')
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
                                    <th>{{ __('Meeting ID') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Username') }}</th>
                                    <th>{{ __('Password') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Time') }}</th>
                                    <th>{{ __('Timezone') }}</th>
                                    <th>{{ __('Created Date') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($meetings as $meeting)
                                    <tr>
                                        <td>{{ $meetings->firstItem() + $loop->index }}</td>
                                        <td>{{ $meeting->meeting_id }}</td>
                                        <td class="text-truncate"><span title="{{ $meeting->title }}"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="right">{{ $meeting->title }}</span></td>
                                        <td class="text-truncate"> <span title="{{ $meeting->description }}"
                                                data-bs-toggle="tooltip" data-bs-placement="right">
                                                {{ $meeting->description ?? '-' }}</span>
                                        </td>
                                        <td>{{ $meeting->user ? $meeting->user->username : '-' }}</td>
                                        <td>{{ $meeting->password ? $meeting->password : '-' }}</td>
                                        <td>{{ $meeting->date ? $meeting->date : '-' }}</td>
                                        <td>{{ $meeting->time ? $meeting->time : '-' }}</td>
                                        <td class="text-truncate">
                                            <span title="{{ $meeting->timezone }}" data-bs-toggle="tooltip"
                                                data-bs-placement="right">
                                                {{ $meeting->timezone ? $meeting->timezone : '-' }}</span>
                                        </td>
                                        <td>
                                            <span title="{{ $meeting->created_at_custom }}" data-bs-toggle="tooltip"
                                                data-bs-placement="right">
                                                {{ $meeting->created_at->diffForHumans() }}</span>
                                        </td>
                                        <td scope="col">
                                            <div class="form-switch">
                                                <input class="form-check-input toggle-meeting-status" type="checkbox"
                                                    data-id="{{ $meeting->id }}"
                                                    value="{{ $meeting->status === 'active' ? 'active' : 'inactive' }}"
                                                    {{ $meeting->status === 'active' ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            <a class="btn" href = "{{ route('admin.meeting.destroy', $meeting->id) }}"
                                                onclick="return confirm('Are you sure you want to delete this meeting?')"
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
                    @if ($meetings->hasPages())
                        <div class="mt-2 ms-2 mb-2">
                            {{ $meetings->links('pagination::bootstrap-5') }}
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
