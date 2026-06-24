@extends('layouts.app')

@section('page', $page)
@section('title', $page)

@section('styles')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/tom-select.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
    @include('include.user.toast')
    <div class="page jm-dashboard">
        @include('include.user.header')
        <div class="page-wrapper">
            <!-- Page body -->
            <div class="page-body mb-0">
                <div class="container-xl">
                    <div class="card">
                        <!-- meeting header start here -->
                        <div class="row g-0 jm-meeting-header flex-row-reverse">
                            <!-- instant meeting & join meeting start here -->
                            <div class="col-12 col-lg-7 col-xl-7">
                                <div
                                    class="jm-header-right d-flex align-items-center justify-content-end flex-column flex-md-row">
                                    <!-- join meeting start here-->
                                    <div class="jm-join-meeting d-flex align-items-center gap-3">
                                        <form id="meetingDashboard" class="mb-0 w-100">
                                            <div class="input-icon w-100">
                                                <span class="input-icon-addon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-keyboard">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path
                                                            d="M2 6m0 2a2 2 0 0 1 2 -2h16a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-16a2 2 0 0 1 -2 -2z" />
                                                        <path d="M6 10l0 .01" />
                                                        <path d="M10 10l0 .01" />
                                                        <path d="M14 10l0 .01" />
                                                        <path d="M18 10l0 .01" />
                                                        <path d="M6 14l0 .01" />
                                                        <path d="M18 14l0 .01" />
                                                        <path d="M10 14l4 .01" />
                                                    </svg>
                                                </span>
                                                <input type="text" class="form-control" maxlength="9" id="conferenceId"
                                                    name="id" placeholder="{{ __('Enter Meeting ID') }}" required>
                                                <button type="submit" href="#"
                                                    class="link jm-join-link">{{ __('JOIN') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- join meeting end here-->
                                </div>
                            </div>
                            <!-- instant meeting & join meeting end here -->
                            <!-- create meeting start here -->
                            <div class="col-12 col-lg-5 col-xl-5">
                                <div class="jm-header-left d-flex justify-content-between align-items-center">
                                    <h2 class="m-0">{{ __('My Meetings') }}</h2>
                                    <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createMeetingModal">

                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M12 5l0 14"></path>
                                            <path d="M5 12l14 0"></path>
                                        </svg>
                                        {{ __('Create Meeting') }}
                                    </a>
                                </div>
                            </div>
                            <!-- create meeting end here -->
                        </div>
                        <!-- meeting header end here -->
                        <div class="row g-0">
                            <!-- emty meeting start here -->
                            <div class="empty" style="height: 47rem;" @if ($firstMeeting) hidden @endif>
                                <p class="empty-title">{{ __('No meeting found') }}</p>
                                <p class="empty-subtitle text-secondary">
                                    {{ __("Try adjusting your search or filter to find what you're looking for.") }}
                                </p>
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-restore me-0 me-sm-2">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M3.06 13a9 9 0 1 0 .49 -4.087" />
                                        <path d="M3 4.001v5h5" />
                                        <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                    </svg>
                                    {{ __('Reset') }}
                                </a>
                            </div>
                            <!-- emty meeting end here -->

                            <div class="col-12 col-lg-5 col-xl-5 border-end meetingDetail meeting-search-box"
                                @if (!$firstMeeting) hidden @endif>
                                <div class="card-header jm-meeting-search d-flex w-100">
                                    <div class="input-icon w-100">
                                        <div class="input-group w-100">
                                            <form id="searchMeeting" action="{{ route('dashboard') }}"
                                                class="d-flex w-100 m-0">
                                                <input type="text" name="search" class="form-control"
                                                    placeholder="{{ __('Search meetings') }}" aria-label="Search"
                                                    autocomplete="off" maxlength="50" value="{{ $search }}" />
                                                <a onclick="document.getElementById('searchMeeting').submit();"
                                                    class="input-group-text jm-join-link ms-2" style="cursor: pointer;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-search">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                                        <path d="M21 21l-6 -6" />
                                                    </svg>
                                                </a>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-0 scrollable" style="max-height: 40rem">
                                    <div class="nav flex-column nav-pills meeting-list" role="tablist">
                                        @foreach ($meetings as $meeting)
                                            <a class="nav-link text-start mw-100 jm-meeting-card" data-bs-toggle="pill"
                                                role="tab" aria-selected="true" data-title="{{ $meeting->title }}"
                                                data-description="{{ $meeting->description }}"
                                                data-id="{{ $meeting->id }}" data-auto="{{ $meeting->meeting_id }}"
                                                data-password="{{ $meeting->password }}"
                                                data-date="{{ formatDate($meeting->date) }}"
                                                data-time="{{ formatTime($meeting->time) }}"
                                                data-timezone="{{ $meeting->timezone }}">
                                                <div class="row align-items-center flex-fill">
                                                    <div class="col text-body">
                                                        <div class="d-flex justify-content-between">
                                                            <h3 class="meeting-title m-0 text-truncate">
                                                                {{ $meeting->title }} </h3>
                                                            <div class="text-secondary created-time text-nowrap"
                                                                title="{{ $meeting->created_at }}">
                                                                {{ $meeting->created_at->diffForHumans() }}</div>
                                                        </div>
                                                        <div class="text-secondary meeting-description text-truncate mb-2">
                                                            {{ $meeting->description }}</div>
                                                        <div class="d-flex justify-content-between">
                                                            <div class="text-secondary text-truncate jm-meeting-id">Meeting
                                                                ID:
                                                                {{ $meeting->meeting_id }}</div>
                                                            <div
                                                                class="text-secondary d-flex jm-meeting-date justify-content-end">
                                                                @if ($meeting->date || $meeting->time || $meeting->timezone)
                                                                    <span class="icon-tabler">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="24" height="24"
                                                                            viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="1.5"
                                                                            stroke-linecap="round" stroke-linejoin="round"
                                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-stats">
                                                                            <path stroke="none" d="M0 0h24v24H0z"
                                                                                fill="none" />
                                                                            <path
                                                                                d="M11.795 21h-6.795a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v4" />
                                                                            <path d="M18 14v4h4" />
                                                                            <path
                                                                                d="M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                                                            <path d="M15 3v4" />
                                                                            <path d="M7 3v4" />
                                                                            <path d="M3 11h16" />
                                                                        </svg>
                                                                    </span>
                                                                    <p class="text-truncate m-0">
                                                                        {{ $meeting->date . ' ' . $meeting->time . ' ' . $meeting->timezone }}
                                                                    </p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                    <div class="mt-2 ms-2">
                                        {{ $meetings->links('pagination::bootstrap-5') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-7 col-xl-7 d-flex flex-column meetingDetail">
                                <div class="card-body scrollable jm-meeting-detail" style="height: 40rem"
                                    @if (!$firstMeeting) hidden @endif>
                                    <!-- meeting details start here-->
                                    <!--mobile header start here -->
                                    <div
                                        class="jm-meeting-heading mb-4 d-flex justify-content-between align-items-center d-block d-md-none">
                                        <a href="#" class="btn btn-outline-primary mobile-back-btn">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-left me-0 me-sm-2">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M5 12l14 0" />
                                                <path d="M5 12l6 6" />
                                                <path d="M5 12l6 -6" />
                                            </svg>
                                            {{ __('Back') }}
                                        </a>
                                        <div class="jm-meeting-actions d-flex">
                                            <a href="#" class="btn btn-outline-primary" data-bs-toggle="modal"
                                                data-bs-target="#Invite">

                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-user-plus me-0 me-sm-2">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                                    <path d="M16 19h6" />
                                                    <path d="M19 16v6" />
                                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                                                </svg>
                                                {{ __('Invite') }}
                                            </a>
                                            <a class="btn btn-outline-primary" data-bs-toggle="modal"
                                                data-bs-target="#editMeetingModal">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-edit me-0 me-sm-2">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                    <path
                                                        d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                    <path d="M16 5l3 3" />
                                                </svg>
                                                {{ __('Edit') }}
                                            </a>
                                            <a class="btn btn-outline-danger delete"
                                                data-id="{{ $firstMeeting ? $firstMeeting->id : '' }}"
                                                data-action="{{ route('deleteMeeting') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-trash me-0 me-sm-2">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M4 7l16 0" />
                                                    <path d="M10 11l0 6" />
                                                    <path d="M14 11l0 6" />
                                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                </svg>
                                                {{ __('Delete') }}
                                            </a>
                                        </div>
                                    </div>
                                    <!--mobile header end  here -->
                                    <div class="jm-meeting-heading mb-4 d-flex justify-content-between align-items-start">
                                        <h2 class="m-0" id="meetingTitleDetail">
                                            {{ $firstMeeting ? $firstMeeting->title : '' }}</h2>
                                        <div class="jm-meeting-actions d-flex d-none d-md-block">
                                            <a href="#" class="btn btn-outline-primary" data-bs-toggle="modal"
                                                data-bs-target="#Invite">

                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-user-plus me-0 me-sm-2">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                                    <path d="M16 19h6" />
                                                    <path d="M19 16v6" />
                                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                                                </svg>
                                                {{ __('Invite') }}
                                            </a>
                                            <a class="btn btn-outline-primary" data-bs-toggle="modal"
                                                data-bs-target="#editMeetingModal">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-edit me-0 me-sm-2">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                    <path
                                                        d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                    <path d="M16 5l3 3" />
                                                </svg>
                                                {{ __('Edit') }}
                                            </a>
                                            <a class="btn btn-outline-danger delete"
                                                data-id="{{ $firstMeeting ? $firstMeeting->id : '' }}"
                                                data-action="{{ route('deleteMeeting') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-trash me-0 me-sm-2">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M4 7l16 0" />
                                                    <path d="M10 11l0 6" />
                                                    <path d="M14 11l0 6" />
                                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                </svg>
                                                {{ __('Delete') }}
                                            </a>
                                        </div>
                                    </div>
                                    <dl class="row">
                                        <dd class="col-3 mb-4">{{ __('Meeting ID') }}:</dd>
                                        <dt class="col-9 mb-4" id="meetingIdDetail">
                                            {{ $firstMeeting ? $firstMeeting->meeting_id : '' }}</dt>
                                        <dd class="col-3 mb-4">{{ __('Password') }}:</dd>
                                        <dt class="col-9 mb-4" id="meetingPasswordDetail">
                                            {{ $firstMeeting && $firstMeeting->password ? $firstMeeting->password : '-' }}
                                        </dt>
                                        <dd class="col-3 mb-4">{{ __('Date') }}:</dd>
                                        <dt class="col-9 mb-4" id="meetingDateDetail">
                                            {{ $firstMeeting && $firstMeeting->date ? formatDate($firstMeeting->date) : '-' }}
                                        </dt>
                                        <dd class="col-3 mb-4">{{ __('Time') }}:</dd>
                                        <dt class="col-9 mb-4" id="meetingTimeDetail">
                                            {{ $firstMeeting && $firstMeeting->time ? formatTime($firstMeeting->time) : '-' }}
                                        </dt>
                                        <dd class="col-3 mb-4">{{ __('Time zone') }}:</dd>
                                        <dt class="col-9 mb-4" id="meetingTimezoneDetail">
                                            {{ $firstMeeting && $firstMeeting->timezone ? $firstMeeting->timezone : '-' }}
                                        </dt>
                                        <dd class="col-3 mb-4">{{ __('Description') }}:</dd>
                                        <dt class="col-9 mb-4" id="meetingDescriptionDetail">
                                            {{ $firstMeeting && $firstMeeting->description ? $firstMeeting->description : '-' }}
                                        </dt>
                                    </dl>
                                    <div class="jm-meeting-actions d-flex">
                                        <a href="{{ $firstMeeting ? route('meeting', ['id' => $firstMeeting->meeting_id]) : '' }}"
                                            class="btn btn-primary" id="meetingStart">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-send-2 me-0 me-sm-2">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M4.698 4.034l16.302 7.966l-16.302 7.966a.503 .503 0 0 1 -.546 -.124a.555 .555 0 0 1 -.12 -.568l2.468 -7.274l-2.468 -7.274a.555 .555 0 0 1 .12 -.568a.503 .503 0 0 1 .546 -.124z" />
                                                <path d="M6.5 12h14.5" />
                                            </svg>
                                            {{ __('Start') }}
                                        </a>
                                        <a id="copyParticularMeeting" class="btn btn-outline-primary"
                                            data-bs-toggle="toast" data-bs-target="#toast-simple">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-copy me-0 me-sm-2">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M7 7m0 2.667a2.667 2.667 0 0 1 2.667 -2.667h8.666a2.667 2.667 0 0 1 2.667 2.667v8.666a2.667 2.667 0 0 1 -2.667 2.667h-8.666a2.667 2.667 0 0 1 -2.667 -2.667z" />
                                                <path
                                                    d="M4.012 16.737a2.005 2.005 0 0 1 -1.012 -1.737v-10c0 -1.1 .9 -2 2 -2h10c.75 0 1.158 .385 1.5 1" />
                                            </svg>
                                            {{ __('Copy link') }}
                                        </a>
                                        <a href="#" class="btn btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#embed">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-code me-0 me-sm-2">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M7 8l-4 4l4 4" />
                                                <path d="M17 8l4 4l-4 4" />
                                                <path d="M14 4l-4 16" />
                                            </svg>
                                            {{ __('Embed') }}
                                        </a>
                                    </div>
                                    <!-- meeting details end here-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="meetingTemplate" style="display: none;">
        <a class="nav-link text-start mw-100 jm-meeting-card" data-bs-toggle="pill" role="tab" aria-selected="true"
            data-title="" data-description="" data-id="" data-auto="" data-password="" data-date=""
            data-time="" data-timezone="">
            <div class="row align-items-center flex-fill">
                <div class="col text-body">
                    <div class="d-flex justify-content-between">
                        <h3 class="m-0"></h3>
                        <div class="text-secondary" title="{{ __('Just Now') }}">{{ __('Just Now') }}</div>
                    </div>
                    <div class="text-secondary text-truncate mb-2"></div>
                    <div class="d-flex justify-content-between">
                        <div class="text-secondary">{{ __('Meeting ID') }}: </div>
                        <div class="text-secondary d-flex jm-meeting-date"></div>
                    </div>
                </div>
            </div>
        </a>
    </div>


    <!-- Libs JS -->
    <!-- create meeting modal start here -->
    <div class="modal modal-blur fade" id="createMeetingModal" tabindex="1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="createMeetingsForm" data-action="{{ route('createMeeting') }}" class="mb-0">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Create Meeting') }} | ID: <span id="createMeetingId"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">{{ __('Title') }}*</label>
                            <input id="title" name="title" type="text" class="form-control"
                                placeholder="{{ __('Enter meeting title') }}" minlength="3" maxlength="100" required
                                autofocus>
                            <small class="invalid-feedback"></small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('Description') }}</label>
                            <textarea id="description" name="description" class="form-control" rows="4"
                                placeholder="{{ __('Enter meeting description') }}"></textarea>
                            <small class="invalid-feedback"></small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('Password') }}</label>
                            <input id="password" name="password" type="text" class="form-control"
                                placeholder="{{ __('Enter meeting password') }}" minlength="4" maxlength="8">
                            <small class="invalid-feedback"></small>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Date') }}</label>
                                    <div class="input-group input-group-flat">
                                        <input id="date" name="date" type="date" class="form-control">
                                        <small class="invalid-feedback"></small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Time') }}</label>
                                    <div class="input-group input-group-flat">
                                        <input id="time" name="time" type="time" class="form-control">
                                        <small class="invalid-feedback"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('Time Zone') }}</label>
                            <select id="timezone" name="timezone" type="text" class="form-select" value="">
                                <option value="">{{ __('Select meeting timezone') }}</option>
                                @foreach ($timezones as $timezone)
                                    <option value="{{ $timezone['value'] }}">{{ $timezone['value'] }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="invalid-feedback"></small>
                        </div>

                        <input type="hidden" id="createMeetingsFormId" name="meeting_id" />
                    </div>
                    <div class="modal-footer">
                        <a href="" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                            {{ __('Cancel') }}
                        </a>
                        <button id="createMeetingButton" class="btn btn-primary ms-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            {{ __('Create new meeting') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- create meeting modal end here -->

    <!-- edit meeting modal start here -->
    <div class="modal modal-blur fade" id="editMeetingModal" tabindex="1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="editMeetingsForm" data-action="{{ route('editMeeting') }}" class="mb-0">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Edit Meeting') }} | ID: <span id="meetingIdEdit"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">{{ __('Title') }}*</label>
                            <input id="titleEdit" name="title" type="text" class="form-control"
                                placeholder="{{ __('Enter meeting title') }}" minlength="3" maxlength="100" required
                                autofocus>
                            <small class="invalid-feedback"></small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('Description') }}</label>
                            <textarea id="descriptionEdit" name="description" class="form-control" rows="4"
                                placeholder="{{ __('Enter meeting description') }}"></textarea>
                            <small class="invalid-feedback"></small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('Password') }}</label>
                            <input id="passwordEdit" name="password" type="text" class="form-control"
                                placeholder="{{ __('Enter meeting password') }}" minlength="4" maxlength="8">
                            <small class="invalid-feedback"></small>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Date') }}</label>
                                    <div class="input-group input-group-flat">
                                        <input id="dateEdit" name="date" type="date" class="form-control">
                                        <small class="invalid-feedback"></small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Time') }}</label>
                                    <div class="input-group input-group-flat">
                                        <input id="timeEdit" name="time" type="time" class="form-control">
                                        <small class="invalid-feedback"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('Time Zone') }}</label>
                            <select id="timezoneEdit" name="timezone" type="text" class="form-select"
                                value="">
                                <option value="">{{ __('Select meeting timezone') }}</option>
                                @foreach ($timezones as $timezone)
                                    <option value="{{ $timezone['value'] }}">{{ $timezone['value'] }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="invalid-feedback"></small>
                        </div>

                        <input type="hidden" id="meetingsFormIdEdit" name="id" />
                    </div>
                    <div class="modal-footer">
                        <a href="" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                            {{ __('Cancel') }}
                        </a>
                        <button id="updateMeetingButton" class="btn btn-primary ms-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-edit me-0 me-sm-2">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                <path d="M16 5l3 3" />
                            </svg>
                            {{ __('Update meeting') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- create meeting modal end here -->

    <!-- invite modal start here -->
    <div class="modal modal-blur fade" id="Invite" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Invite People') }}</h5>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-gem ms-2" viewBox="0 0 16 16">
                                <path
                                    d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
                            </svg>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="inviteForm" class="mb-0">
                    <div class="modal-body">
                        <p>This feature is available in paid versions.</p>
                    </div>
                    <div class="modal-body p-0 jm-invite-list">
                        <div class="list-group list-group-flush"></div>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-link link-secondary" data-bs-dismiss="modal">
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- invite modal end here -->
    <!-- embed modal start here -->
    <div class="modal modal-blur fade" id="embed" tabindex="1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Embed') }}</h5>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-gem ms-2" viewBox="0 0 16 16">
                                <path
                                    d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
                            </svg>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>This feature is available in the paid version.</p>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                        {{ __('Cancel') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- embed modal end here -->
@endsection

@section('script')
    <script>
        let meetingId;
        let meetingExist = "{{ !$meetings->isEmpty() }}" || null;
        let timeLimit = "{{ $timeLimit }}";


        if (meetingExist) {
            $('.jm-meeting-card:first').addClass('active');
            meetingId = "{{ $firstMeeting ? $firstMeeting->id : '' }}";
        }
    </script>
    <script src="{{ asset('/js/dashboard.js') }}"></script>
@endsection
