@extends('layouts.app')

@section('title', $page . ' - ' . $meeting->title)

@section('styles')
    <link href="{{ asset('/css/meeting.css?version=') . getVersion() }}" rel="stylesheet" />
@endsection

@section('content')
        @include('include.user.header')

        <canvas id="audioOnly" hidden></canvas>
        <div class="page jm-meeting meeting-details">
            <div class="page-wrapper">
                <!-- Page body -->
                <div class="page-body d-flex justify-content-center align-items-center">
                    <div class="d-flex justify-content-center align-items-center jm-meeting-center">
                        <div class="container-xl">
                            <div class="jm-meeting-start-space d-flex justify-content-center align-items-center">
                                <!-- Video start here -->
                                <div class="jm-video-part-width">
                                    <div class="jm-video-part position-relative d-flex mb-3">
                                        <video id="previewVideo" class="cam" autoplay playsinline muted></video>
                                        <div
                                            class="jm-no-camera-found position-absolute w-100 h-100 top-0 d-flex align-items-center justify-content-center z-1">
                                            <h3>{{ __('Camera is off') }}</h3>
                                        </div>
                                        <div
                                            class="jm-video-action position-absolute w-100 bottom-0 mb-4 d-flex align-items-center justify-content-center z-3">
                                            <a id="toggleCameraPreview" class="btn btn-outline-light disabled"
                                                title="{{ __('On/Off Camera') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                    fill="currentColor" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd"
                                                        d="M10.961 12.365a2 2 0 0 0 .522-1.103l3.11 1.382A1 1 0 0 0 16 11.731V4.269a1 1 0 0 0-1.406-.913l-3.111 1.382A2 2 0 0 0 9.5 3H4.272l.714 1H9.5a1 1 0 0 1 1 1v6a1 1 0 0 1-.144.518zM1.428 4.18A1 1 0 0 0 1 5v6a1 1 0 0 0 1 1h5.014l.714 1H2a2 2 0 0 1-2-2V5c0-.675.334-1.272.847-1.634zM15 11.73l-3.5-1.555v-4.35L15 4.269zm-4.407 3.56-10-14 .814-.58 10 14z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                    <!-- video error start here -->
                                    <div class="jm-video-error d-flex justify-content-between">
                                        <a id="micError" class="btn btn-outline-danger small p-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                fill="currentColor" viewBox="0 0 16 16">
                                                <path
                                                    d="M13 8c0 .564-.094 1.107-.266 1.613l-.814-.814A4 4 0 0 0 12 8V7a.5.5 0 0 1 1 0zm-5 4c.818 0 1.578-.245 2.212-.667l.718.719a5 5 0 0 1-2.43.923V15h3a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1h3v-2.025A5 5 0 0 1 3 8V7a.5.5 0 0 1 1 0v1a4 4 0 0 0 4 4m3-9v4.879l-1-1V3a2 2 0 0 0-3.997-.118l-.845-.845A3.001 3.001 0 0 1 11 3" />
                                                <path
                                                    d="m9.486 10.607-.748-.748A2 2 0 0 1 6 8v-.878l-1-1V8a3 3 0 0 0 4.486 2.607m-7.84-9.253 12 12 .708-.708-12-12z" />
                                            </svg>&nbsp;
                                            <span class="small"></span>
                                        </a>
                                        <a id="camError" class="btn btn-outline-danger small p-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                fill="currentColor" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd"
                                                    d="M10.961 12.365a2 2 0 0 0 .522-1.103l3.11 1.382A1 1 0 0 0 16 11.731V4.269a1 1 0 0 0-1.406-.913l-3.111 1.382A2 2 0 0 0 9.5 3H4.272l.714 1H9.5a1 1 0 0 1 1 1v6a1 1 0 0 1-.144.518zM1.428 4.18A1 1 0 0 0 1 5v6a1 1 0 0 0 1 1h5.014l.714 1H2a2 2 0 0 1-2-2V5c0-.675.334-1.272.847-1.634zM15 11.73l-3.5-1.555v-4.35L15 4.269zm-4.407 3.56-10-14 .814-.58 10 14z" />
                                            </svg></i>&nbsp;
                                            <span class="small"></span>
                                        </a>
                                    </div>
                                    <!-- video error end here -->
                                </div>
                                <!-- Video end here -->
                                <!-- meeting details start here -->
                                <div class="jm-meeting-start-details-width">
                                    <form id="passwordCheck">
                                        <div class="jm-meeting-start-details d-flex align-items-start flex-column">
                                            <div class="jm-meeting-start-heading">
                                                <span class="badge bg-blue text-blue-fg">
                                                    @if ($meeting->timeLimit == '-1')
                                                        {{ __('Unlimited Minutes') }}
                                                    @else
                                                        {{ $meeting->timeLimit }} {{ __('Minutes') }}
                                                    @endif
                                                </span>
                                                <h2 class="mb-1 mt-2">{{ $meeting->title }}</h2>
                                                <p class="m-0">{{ $meeting->description }}</p>
                                            </div>

                                            <div class="jm-meeting-start-body d-flex flex-column">
                                                <div class="d-flex jm-meeting-start-item-list">
                                                    <div class="jm-meeting-start-item">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-video me-1">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path
                                                                d="M15 10l4.553 -2.276a1 1 0 0 1 1.447 .894v6.764a1 1 0 0 1 -1.447 .894l-4.553 -2.276v-4z" />
                                                            <path
                                                                d="M3 6m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z" />
                                                        </svg>
                                                        <strong>{{ $meeting->meeting_id }}</strong>
                                                    </div>
                                                    <div class="jm-meeting-start-item">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-calendar me-1">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path
                                                                d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" />
                                                            <path d="M16 3v4" />
                                                            <path d="M8 3v4" />
                                                            <path d="M4 11h16" />
                                                            <path d="M11 15h1" />
                                                            <path d="M12 15v3" />
                                                        </svg>
                                                        <strong
                                                            id="date">{{ $meeting->date ? formatDate($meeting->date) : '00-00-0000' }}</strong>
                                                    </div>
                                                    <div class="jm-meeting-start-item">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-clock-hour-4 me-1">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                                            <path d="M12 12l3 2" />
                                                            <path d="M12 7v5" />
                                                        </svg>
                                                        <strong
                                                            id="time">{{ $meeting->time ? formatTime($meeting->time) : '00:00 00' }}</strong>
                                                    </div>
                                                </div>
                                                <div class="jm-meeting-start-item">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-timezone me-1">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M20.884 10.554a9 9 0 1 0 -10.337 10.328" />
                                                        <path d="M3.6 9h16.8" />
                                                        <path d="M3.6 15h6.9" />
                                                        <path d="M11.5 3a17 17 0 0 0 -1.502 14.954" />
                                                        <path d="M12.5 3a17 17 0 0 1 2.52 7.603" />
                                                        <path d="M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                                        <path d="M18 16.5v1.5l.5 .5" />
                                                    </svg>
                                                    <strong
                                                        id="timezone">{{ $meeting->timezone ? $meeting->timezone : '-' }}</strong>
                                                </div>

                                                <div class="form-group" @if (Auth::check()) hidden @endif>
                                                    <label class="form-label" for="username">{{ __('Username') }}</label>
                                                    <input type="text" class="form-control" id="username"
                                                        placeholder="{{ __('Enter your name') }}"
                                                        value="{{ $meeting->username }}" maxlength="25">
                                                </div>

                                                @if ($meeting->password)
                                                    <div class="form-group">
                                                        <label class="form-label" for="password">{{ __('Meeting Password') }}
                                                            *</label>
                                                        <input type="text" id="password" class="form-control"
                                                            name="password" placeholder="{{ __('Enter meeting password') }}">
                                                    </div>
                                                    <input type="hidden" name="id" value="{{ $meeting->id }}">
                                                @endif
                                            </div>
                                            <div class="jm-meeting-start-action d-flex">
                                                <button id="joinMeeting" type="submit" class="btn btn-primary" disabled>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-send-2">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path
                                                            d="M4.698 4.034l16.302 7.966l-16.302 7.966a.503 .503 0 0 1 -.546 -.124a.555 .555 0 0 1 -.12 -.568l2.468 -7.274l-2.468 -7.274a.555 .555 0 0 1 .12 -.568a.503 .503 0 0 1 .546 -.124z">
                                                        </path>
                                                        <path d="M6.5 12h14.5"></path>
                                                    </svg>
                                                    <span>{{ __('Loading') }}</span>
                                                </button>
                                                <a class="btn btn-outline-primary" data-bs-toggle="modal"
                                                    data-bs-target="#modal-setting">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-info-circle m-0">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                                        <path d="M12 9h.01" />
                                                        <path d="M11 12h1v4h1" />
                                                    </svg>
                                                </a>
                                                <a class="btn btn-outline-primary add">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-share m-0">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M6 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                                        <path d="M18 6m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                                        <path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                                        <path d="M8.7 10.7l6.6 -3.4" />
                                                        <path d="M8.7 13.3l6.6 3.4" />
                                                    </svg>
                                                </a>
                                            </div>
                                            @if (getSetting('MODERATOR_RIGHTS') == 'enabled')
                                                @guest
                                                    <div class="alert alert-warning " role="alert">
                                                        <div class="alert-icon">
                                                            <!-- Download SVG icon from http://tabler.io/icons/icon/alert-triangle -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                                class="icon alert-icon icon-2">
                                                                <path d="M12 9v4"></path>
                                                                <path
                                                                    d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z">
                                                                </path>
                                                                <path d="M12 16h.01"></path>
                                                            </svg>
                                                        </div>
                                                        {{ __('If you are the moderator, please') }}
                                                        <a href="{{ route('login') }}"
                                                            class="alert-action">{{ __('Login') }}</a>
                                                    </div>
                                                @endguest
                                            @endif
                                            <div id="error">
                                                <p>{{ __('Could not connect to the server, please try refreshing the page') }}
                                                </p>
                                                @if ($meeting->isAdmin)
                                                    <a href="{{ route('admin.signaling-server') }}" target="_blank"><span
                                                            class="badge bg-yellow text-yellow-fg p-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" fill="currentColor"
                                                                class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                                                                <path
                                                                    d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                                                            </svg>
                                                            {{ __('Troubleshooting steps (Visible to the admin only)') }}</span></a>
                                                @endif
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- meeting details end here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- meetinhg end here  -->
        <!-- start call start here  -->
        <div class="page jm-start-call meeting-section d-none">
            <div class="page-wrapper">
                <!-- mobile header start here -->
                <div class="jm-start-call-mobile-header d-flex d-lg-none justify-content-between">
                    <div class="jm-call-start-logo d-flex align-items-center">
                        <img src="{{ asset('/storage/images/' . getSetting('FAVICON')) }}" alt="{{ __('Logo') }}"
                            loading="lazy" width="38">
                        <div class="jm-start-call-name">
                            <p class="m-0 meeting-id"></p>
                            <h3 class="m-0 timer">00:00:00</h3>
                        </div>
                    </div>
                    <div class="dropdown">
                        <a class="btn-action dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                <path
                                    d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
                            </svg>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end" data-bs-theme="dark">
                            <a class="dropdown-item muteAll">{{ __('Mute All') }} <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
        class="bi bi-gem" viewBox="0 0 16 16">
        <path
            d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
    </svg></a>
                            <a class="dropdown-item showParticipantList" data-bs-toggle="modal"
                                data-bs-target="#modal-participants">{{ __('Participants') }}
                                <span class="badge bg-primary participant-count"></span></a>
                            <a class="dropdown-item openChat">{{ __('Group Chat') }}
                                <span class="groupchat-count"></span></a>
                            <a class="dropdown-item openChatGPT">{{ __('AI Chatbot') }} <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
        class="bi bi-gem" viewBox="0 0 16 16">
        <path
            d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
    </svg>
                                <span class="chatbot-count"></span></a>
                            <a class="dropdown-item recording">{{ __('Start/Stop Recording') }} <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
        class="bi bi-gem" viewBox="0 0 16 16">
        <path
            d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
    </svg></a>
                            <a class="dropdown-item openSettings">{{ __('Open Settings') }}</a>
                        </div>
                    </div>
                </div>
                <!-- mobile header end here -->
                <!-- Page body -->
                <div class="page-body mb-0 d-flex justify-content-center align-items-center">
                    <div class="d-flex justify-content-center align-items-center jm-meeting-center">
                        <div class="container-xl">
                            <div class="jm-meeting-start-space d-flex justify-content-between align-items-center">
                                <!-- start call videos start here -->
                                <div id="videos" class="jm-start-call-uservideo d-flex">
                                    <!-- user video item 1 -->
                                    <div id="selfContainer" class="videoContainer ot-layout">
                                        <video id="localVideo" class="cam" autoplay playsinline muted></video>

                                        <div class="jm-call-start-avatar position-absolute top-50 start-50 translate-middle">
                                            @if (getAuthUserInfo('avatar'))
                                                <img src="{{ asset('storage/avatars/' . getAuthUserInfo('avatar')) }}"
                                                    loading="lazy" class="avatar avatar-xl rounded" />
                                            @else
                                                <span
                                                    class="avatar avatar-xl rounded">{{ getAuthUserInfo('username') ? getAuthUserInfo('username')[0] : '' }}</span>
                                            @endif
                                        </div>

                                        <div class="jm-start-call-username position-absolute bottom-0 start-0 m-2">
                                            <span class="tag local-user-name">
                                                {{ __('You') }}
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="moderator-icon" viewBox="0 0 16 16"
                                                    title="{{ __('Moderator') }}"
                                                    @if (!$meeting->isModerator) style="display: none" @endif>
                                                    <path
                                                        d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
                                                </svg>
                                            </span>
                                        </div>
                                    </div>

                                    <div id="screenContainer" class="videoContainer OT_big screen">
                                        <video id="localScreenVideo" autoplay playsinline muted></video>
                                        <div class="jm-start-call-username position-absolute bottom-0 start-0 m-2">
                                            <span class="tag">
                                                {{ __('Your screen') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <!-- start call videos end here -->
                                <!-- Chat start here -->
                                <div id="groupChat" data-bs-theme="dark"
                                    class="card jm-start-call-sidepanel jm-start-call-chat chat-hide">
                                    <div class="card-header">
                                        <h3 class="card-title">{{ __('Group Chat') }}</h3>
                                        <div class="card-actions btn-actions">
                                            <a class="btn-action close-panel">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                                    <path
                                                        d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-body scrollable jm-card-body">
                                        <div class="chat">
                                            <div class="chat-bubbles">
                                                <div class="empty-chat-body">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-chat-left-text chat-icon"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z" />
                                                        <path
                                                            d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5M3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6m0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <form id="chatForm">
                                            <div class="input-group input-group-flat">
                                                <input type="text" id="messageInput" class="form-control"
                                                    autocomplete="off" placeholder="{{ __('Type a message') }}"
                                                    maxlength="250">
                                                <span class="input-group-text">
                                                    <a id="emojiPicker" class="link-secondary" data-bs-toggle="tooltip"
                                                        aria-label="{{ __('Emoji') }}"
                                                        data-bs-original-title="{{ __('Emoji') }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            fill="currentColor" class="bi bi-emoji-smile"
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                                            <path
                                                                d="M4.285 9.567a.5.5 0 0 1 .683.183A3.5 3.5 0 0 0 8 11.5a3.5 3.5 0 0 0 3.032-1.75.5.5 0 1 1 .866.5A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1-3.898-2.25.5.5 0 0 1 .183-.683M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5m4 0c0 .828-.448 1.5-1 1.5s-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5" />
                                                        </svg>
                                                    </a>
                                                    <a id="selectFile" class="link-secondary ms-2" data-bs-toggle="tooltip"
                                                        aria-label="{{ __('Attach File') }}"
                                                        data-bs-original-title="{{ __('Attach File') }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            fill="currentColor" class="bi bi-paperclip" viewBox="0 0 16 16">
                                                            <path
                                                                d="M4.5 3a2.5 2.5 0 0 1 5 0v9a1.5 1.5 0 0 1-3 0V5a.5.5 0 0 1 1 0v7a.5.5 0 0 0 1 0V3a1.5 1.5 0 1 0-3 0v9a2.5 2.5 0 0 0 5 0V5a.5.5 0 0 1 1 0v7a3.5 3.5 0 1 1-7 0z" />
                                                        </svg>
                                                    </a>
                                                    <a id="sendMessage" class="link-secondary ms-2" data-bs-toggle="tooltip"
                                                        aria-label="{{ __('Send') }}"
                                                        data-bs-original-title="{{ __('Send') }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            fill="currentColor" class="bi bi-send" viewBox="0 0 16 16">
                                                            <path
                                                                d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576zm6.787-8.201L1.591 6.602l4.339 2.76z" />
                                                        </svg>
                                                    </a>
                                                </span>
                                            </div>
                                        </form>
                                        <input type="file" name="file" id="file" hidden />
                                    </div>
                                </div>
                                <!-- Chat end here -->

                                <!-- ChatGPT start here -->

                                <div id="chatGPTChat" data-bs-theme="dark"
                                    class="card jm-start-call-sidepanel jm-start-call-chat chat-hide">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            <img src="/images/chatgpt-logo.png" width="30" loading="lazy"
                                                alt="{{ __('ChatGPT') }}" />
                                            {{ __('ChatGPT') }}
                                        </h3>
                                        <div class="card-actions btn-actions">
                                            <a class="btn-action close-panel-chatgpt">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                                    <path
                                                        d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-body scrollable jm-card-body">
                                        <div class="chat">
                                            <div class="chat-bubbles">
                                                <div class="empty-chat-body">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-chat-left-text chat-icon"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z" />
                                                        <path
                                                            d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5M3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6m0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <form id="chatGPTchatForm">
                                            <div class="input-group input-group-flat">
                                                <input type="text" id="chatGPTmessageInput" class="form-control"
                                                    autocomplete="off" placeholder="{{ __('Available in Paid versions.') }}"
                                                    maxlength="250" disabled>
                                                <span class="input-group-text">
                                                    <a id="chatGPTSendMessage" class="link-secondary ms-2 disabled" style="pointer-events: none;"
                                                        data-bs-toggle="tooltip" aria-label="{{ __('Send') }}"
                                                        data-bs-original-title="{{ __('Send') }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            fill="currentColor" class="bi bi-send" viewBox="0 0 16 16">
                                                            <path
                                                                d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576zm6.787-8.201L1.591 6.602l4.339 2.76z" />
                                                        </svg>
                                                    </a>
                                                </span>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- Chat end here -->

                                @includeIf('transcription-summary::meeting-scripts')
                                @stack('meeting_livecaption-panel')

                                <!-- white board start here -->
                                <div class="card jm-start-call-sidepanel jm-start-call-whiteboard d-none">
                                    <div class="card-header">
                                        <h3 class="card-title">{{ __('Whiteboard') }}</h3>
                                        <div class="card-actions btn-actions">
                                            <a class="btn-action">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M18 6l-12 12" />
                                                    <path d="M6 6l12 12" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-body scrollable jm-card-body">
                                        <!-- add here white board iframe-->
                                    </div>
                                </div>
                                <!-- white board end here -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- call start action button start here -->
                <div class="jm-call-start-actin d-flex justify-content-between">
                    <!-- logo and name start here -->
                    <div class="jm-call-start-logo d-flex align-items-center d-none d-lg-flex">
                        <img src="{{ asset('/storage/images/' . getSetting('FAVICON')) }}" alt="{{ __('Logo') }}"
                            loading="lazy" width="38">
                        <div class="jm-start-call-name">
                            <p class="m-0 meeting-id"></p>
                            <h3 class="m-0 timer">00:00:00</h3>
                        </div>
                    </div>
                    <!-- logo and name end here -->
                    <!-- main action center button start here-->
                    <div class="jm-video-action d-flex align-items-center justify-content-center meeting-buttons">
                        <a class="btn btn-outline-light" title="{{ __('Mute/Unmute Mic') }}" id="toggleMic">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                viewBox="0 0 16 16">
                                <path
                                    d="M3.5 6.5A.5.5 0 0 1 4 7v1a4 4 0 0 0 8 0V7a.5.5 0 0 1 1 0v1a5 5 0 0 1-4.5 4.975V15h3a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1h3v-2.025A5 5 0 0 1 3 8V7a.5.5 0 0 1 .5-.5" />
                                <path
                                    d="M10 8a2 2 0 1 1-4 0V3a2 2 0 1 1 4 0zM8 0a3 3 0 0 0-3 3v5a3 3 0 0 0 6 0V3a3 3 0 0 0-3-3" />
                            </svg>
                        </a>
                        <a class="btn btn-outline-light" title="{{ __('On/Off Camera') }}" id="toggleVideo">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M0 5a2 2 0 0 1 2-2h7.5a2 2 0 0 1 1.983 1.738l3.11-1.382A1 1 0 0 1 16 4.269v7.462a1 1 0 0 1-1.406.913l-3.111-1.382A2 2 0 0 1 9.5 13H2a2 2 0 0 1-2-2zm11.5 5.175 3.5 1.556V4.269l-3.5 1.556zM2 4a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h7.5a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1z" />
                            </svg>
                        </a>
                        <div class="dropdown">
                            <a class="btn btn-outline-light" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false" title="{{ __('Present') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                    viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm8.5 9.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707z" />
                                </svg>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" data-bs-theme="dark">
                                <a class="dropdown-item d-none d-lg-flex" id="screenShare">
                                    {{ __('Screen') }} <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
        class="bi bi-gem" viewBox="0 0 16 16">
        <path
            d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
    </svg>
                                </a>
                                <a class="dropdown-item" id="whiteboard"> {{ __('Whiteboard') }} <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
    class="bi bi-gem" viewBox="0 0 16 16">
        <path
            d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
    </svg>
                                </a>
                            </div>
                        </div>
                        <a class="btn btn-outline-light" title="{{ __('Raise Hand') }}" id="raiseHand">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                viewBox="0 0 16 16">
                                <path
                                    d="M6.75 1a.75.75 0 0 1 .75.75V8a.5.5 0 0 0 1 0V5.467l.086-.004c.317-.012.637-.008.816.027.134.027.294.096.448.182.077.042.15.147.15.314V8a.5.5 0 0 0 1 0V6.435l.106-.01c.316-.024.584-.01.708.04.118.046.3.207.486.43.081.096.15.19.2.259V8.5a.5.5 0 1 0 1 0v-1h.342a1 1 0 0 1 .995 1.1l-.271 2.715a2.5 2.5 0 0 1-.317.991l-1.395 2.442a.5.5 0 0 1-.434.252H6.118a.5.5 0 0 1-.447-.276l-1.232-2.465-2.512-4.185a.517.517 0 0 1 .809-.631l2.41 2.41A.5.5 0 0 0 6 9.5V1.75A.75.75 0 0 1 6.75 1M8.5 4.466V1.75a1.75 1.75 0 1 0-3.5 0v6.543L3.443 6.736A1.517 1.517 0 0 0 1.07 8.588l2.491 4.153 1.215 2.43A1.5 1.5 0 0 0 6.118 16h6.302a1.5 1.5 0 0 0 1.302-.756l1.395-2.441a3.5 3.5 0 0 0 .444-1.389l.271-2.715a2 2 0 0 0-1.99-2.199h-.581a5 5 0 0 0-.195-.248c-.191-.229-.51-.568-.88-.716-.364-.146-.846-.132-1.158-.108l-.132.012a1.26 1.26 0 0 0-.56-.642 2.6 2.6 0 0 0-.738-.288c-.31-.062-.739-.058-1.05-.046zm2.094 2.025" />
                            </svg>
                        </a>
                        <a class="btn btn-danger" title="{{ __('Leave Meeting') }}" id="leave">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z" />
                                <path fill-rule="evenodd"
                                    d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z" />
                            </svg>
                        </a>
                    </div>
                    <!-- main action center button end here-->
                    <!-- main action right button start here-->
                    <div class="jm-video-action d-flex align-items-center justify-content-center d-none d-lg-flex">
                        <div class="dropdown">
                            <a class="d-none d-lg-flex" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="white"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
                                </svg>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" data-bs-theme="dark">
                                <a class="dropdown-item muteAll">{{ __('Mute All') }} <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
        class="bi bi-gem" viewBox="0 0 16 16">
        <path
            d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
    </svg></a>
                                <a class="dropdown-item showParticipantList" data-bs-toggle="modal"
                                    data-bs-target="#modal-participants">{{ __('Participants') }}
                                    <span class="badge bg-primary participant-count"></span></a>
                                <a class="dropdown-item openChat">{{ __('Group Chat') }} <span
                                        class="groupchat-count"></span></a>
                                <a class="dropdown-item openChatGPT">{{ __('AI Chatbot') }} <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
        class="bi bi-gem" viewBox="0 0 16 16">
        <path
            d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
    </svg><span
                                        class="chatbot-count"></span></a>
                                <a class="dropdown-item recording">{{ __('Start/Stop Recording') }} <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
        class="bi bi-gem" viewBox="0 0 16 16">
        <path
            d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
    </svg></a>
                                <a class="dropdown-item openSettings">{{ __('Open Settings') }}</a>
                                @stack('meeting_action_buttons')

                            </div>
                        </div>
                    </div>
                    <!-- main action right button end here-->
                </div>
                <!-- call start  action button end here -->
            </div>
        </div>
        <!-- start call end here  -->
        <!-- meeting info modal start here -->
        <div class="modal modal-blur fade" id="modal-setting" tabindex="-1" role="dialog"
            aria-labelledby="modalSettingLabel" aria-modal="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalSettingLabel">{{ __('Settings') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body jm-meeting-shortcutkey">
                        <dl class="row m-0">
                            <dt class="col-4 mb-3">{{ __('Shortcut Keys') }}</dt>
                            <dt class="col-8 mb-3">{{ __('Action') }}</dt>
                            <dd class="col-4 mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="currentColor"
                                    class="icon icon-tabler icons-tabler-filled icon-tabler-square-letter-c">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M19 2a3 3 0 0 1 3 3v14a3 3 0 0 1 -3 3h-14a3 3 0 0 1 -3 -3v-14a3 3 0 0 1 3 -3zm-7 5a3 3 0 0 0 -3 3v4a3 3 0 0 0 6 0a1 1 0 0 0 -1.993 -.117l-.007 .117a1 1 0 0 1 -2 0v-4a1 1 0 0 1 1.993 -.117l.007 .117a1 1 0 0 0 2 0a3 3 0 0 0 -3 -3" />
                                </svg>
                            </dd>
                            <dd class="col-8 mb-3">{{ __('Chat') }}</dd>
                            <dd class="col-4 mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="currentColor"
                                    class="icon icon-tabler icons-tabler-filled icon-tabler-square-letter-f">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M19 2a3 3 0 0 1 3 3v14a3 3 0 0 1 -3 3h-14a3 3 0 0 1 -3 -3v-14a3 3 0 0 1 3 -3zm-5 5h-4a1 1 0 0 0 -1 1v8a1 1 0 0 0 1 1l.117 -.007a1 1 0 0 0 .883 -.993v-3h2a1 1 0 0 0 .993 -.883l.007 -.117a1 1 0 0 0 -1 -1h-2v-2h3a1 1 0 0 0 0 -2" />
                                </svg>
                            </dd>
                            <dd class="col-8 mb-3">{{ __('Attach File') }}</dd>
                            <dd class="col-4 mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="currentColor"
                                    class="icon icon-tabler icons-tabler-filled icon-tabler-square-letter-a">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M19 2a3 3 0 0 1 3 3v14a3 3 0 0 1 -3 3h-14a3 3 0 0 1 -3 -3v-14a3 3 0 0 1 3 -3zm-7 5a3 3 0 0 0 -3 3v6a1 1 0 0 0 2 0v-2h2v2a1 1 0 0 0 .883 .993l.117 .007a1 1 0 0 0 1 -1v-6a3 3 0 0 0 -3 -3m0 2a1 1 0 0 1 1 1v2h-2v-2a1 1 0 0 1 .883 -.993z" />
                                </svg>
                            </dd>
                            <dd class="col-8 mb-3">{{ __('Mute/Unmute Audio') }}</dd>
                            <dd class="col-4 mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="currentColor"
                                    class="icon icon-tabler icons-tabler-filled icon-tabler-square-letter-l">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M19 2a3 3 0 0 1 3 3v14a3 3 0 0 1 -3 3h-14a3 3 0 0 1 -3 -3v-14a3 3 0 0 1 3 -3zm-9 5a1 1 0 0 0 -1 1v8a1 1 0 0 0 1 1h4a1 1 0 0 0 1 -1l-.007 -.117a1 1 0 0 0 -.993 -.883h-3v-7a1 1 0 0 0 -1 -1" />
                                </svg>
                            </dd>
                            <dd class="col-8 mb-3">{{ __('Leave Meeting') }}</dd>
                            <dd class="col-4 mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="currentColor"
                                    class="icon icon-tabler icons-tabler-filled icon-tabler-square-letter-v">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M19 2a3 3 0 0 1 3 3v14a3 3 0 0 1 -3 3h-14a3 3 0 0 1 -3 -3v-14a3 3 0 0 1 3 -3zm-4.757 5.03a1 1 0 0 0 -1.213 .727l-1.03 4.118l-1.03 -4.118a1 1 0 1 0 -1.94 .486l2 8c.252 1.01 1.688 1.01 1.94 0l2 -8a1 1 0 0 0 -.727 -1.213" />
                                </svg>
                            </dd>
                            <dd class="col-8 mb-3">{{ __('On/Off Video') }}</dd>
                            <dd class="col-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="currentColor"
                                    class="icon icon-tabler icons-tabler-filled icon-tabler-square-letter-s">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M19 2a3 3 0 0 1 3 3v14a3 3 0 0 1 -3 3h-14a3 3 0 0 1 -3 -3v-14a3 3 0 0 1 3 -3zm-6 5h-2a2 2 0 0 0 -2 2v2a2 2 0 0 0 2 2h2v2h-2a1 1 0 0 0 -2 0a2 2 0 0 0 2 2h2a2 2 0 0 0 2 -2v-2a2 2 0 0 0 -2 -2h-2v-2h2l.007 .117a1 1 0 0 0 1.993 -.117a2 2 0 0 0 -2 -2" />
                                </svg>
                            </dd>
                            <dd class="col-8">{{ __('Screen Share') }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
        <!-- meeting info modal end here -->
        <!-- file preview modal start here -->
        <div class="modal modal-blur fade" id="filePreviewModal" tabindex="-1" role="dialog"
            aria-labelledby="filePreviewLabel" aria-modal="true" data-bs-theme="dark">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="filePreviewLabel">{{ __('File Preview') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body jm-meeting-shortcutkey">
                        <div class="jm-chat-file-preview text-center">
                            <img id="previewImage" src="" width="300" height="300" loading="lazy">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-link link-secondary" data-bs-dismiss="modal">
                            {{ __('Cancel') }}
                        </a>
                        <a id="sendFile" class="btn btn-primary ms-auto" data-bs-dismiss="modal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-send-2">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path
                                    d="M4.698 4.034l16.302 7.966l-16.302 7.966a.503 .503 0 0 1 -.546 -.124a.555 .555 0 0 1 -.12 -.568l2.468 -7.274l-2.468 -7.274a.555 .555 0 0 1 .12 -.568a.503 .503 0 0 1 .546 -.124z">
                                </path>
                                <path d="M6.5 12h14.5"></path>
                            </svg>
                            {{ __('Send') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- file preview  modal end here -->

        <div class="modal modal-blur fade" id="displayModal" tabindex="-1" role="dialog" aria-modal="true"
            aria-labelledby="displayModalLabel" data-bs-theme="dark">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="displayModalLabel">{{ __('File Display') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="jm-chat-file-preview text-center">
                            <img id="displayImage" loading="lazy" src="" />
                            <p id="displayFilename"></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-link link-secondary" data-bs-dismiss="modal">
                            {{ __('Cancel') }}
                        </a>
                        <a id="downloadFile" class="btn btn-primary ms-auto" data-bs-dismiss="modal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-download">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                <path d="M7 11l5 5l5 -5" />
                                <path d="M12 4l0 12" />
                            </svg>
                            {{ __('Download') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Participants modal start here -->
        <div class="modal modal-blur fade" id="modal-participants" tabindex="-1" role="dialog"
            aria-labelledby="participantsLabel" aria-modal="true" data-bs-theme="dark">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="participantsLabel">{{ __('Participants') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body jm-invite-list text-center">
                        <dl id="participantListBody" class="row m-0">
                            <dt class="col-4 mb-3">#</dt>
                            <dt class="col-8 mb-3">{{ __('Name') }}</dt>

                            <dd class="col-4 mb-3 participant-list-number">
                                1
                            </dd>
                            <dd class="col-8 mb-3">
                                {{ __('You') }}
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="moderator-icon" viewBox="0 0 16 16" title="{{ __('Moderator') }}"
                                    @if (!$meeting->isModerator) style="display: none" @endif>
                                    <path
                                        d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
                                </svg>
                            </dd>
                        </dl>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-link link-secondary" data-bs-dismiss="modal">
                            {{ __('Cancel') }}
                        </a>
                        <a class="btn btn-primary ms-auto add" data-bs-dismiss="modal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-user-plus me-0 me-lg-2">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                                <path d="M16 19h6"></path>
                                <path d="M19 16v6"></path>
                                <path d="M6 21v-2a4 4 0 0 1 4 -4h4"></path>
                            </svg>
                            {{ __('Invite') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Participants modal end here -->
        <!-- setting modal start here -->
        <div class="modal modal-blur fade" id="settings" tabindex="-1" role="dialog" aria-labelledby="settingsLabel"
            aria-modal="true" data-bs-theme="dark">
            <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="settingsLabel">{{ __('Setting') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="videoQualitySelect">{{ __('Video Quality') }}</label>
                            <select type="text" class="form-select" id="videoQualitySelect">
                                <option id="QVGA" data-width="320" data-height="240">{{ __('QVGA') }}</option>
                                <option id="VGA" data-width="640" data-height="480" selected>{{ __('VGA') }}
                                </option>
                                <option id="HD" data-width="1280" data-height="720">{{ __('HD') }} 💎</option>
                                <option id="FHD" data-width="1920" data-height="1080">{{ __('FHD') }} 💎</option>
                                <option id="4K" data-width="3840" data-height="2160">{{ __('4K') }} 💎</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="audioSource">{{ __('Audio input source') }}</label>
                            <select type="text" class="form-select" id="audioSource"></select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="videoSource">{{ __('Video source') }}</label>
                            <select type="text" class="form-select" id="videoSource" value=""></select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="videoObjectFit">{{ __('Video object-fit') }}</label>
                            <select id="videoObjectFit" class="form-select">
                                <option value="cover">{{ __('Cover') }}</option>
                                <option value="contain">{{ __('Contain') }}</option>
                                <option value="fill">{{ __('Fill') }}</option>
                                <option value="none">{{ __('None') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-link link-secondary" data-bs-dismiss="modal">
                            {{ __('Cancel') }}
                        </a>
                        <a class="btn btn-primary ms-auto" data-bs-dismiss="modal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-circle-check">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                <path d="M9 12l2 2l4 -4" />
                            </svg>
                            {{ __('Done') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- setting modal end here -->
        <!-- whiteboard modal start here -->
        <div class="modal modal-blur fade" id="showWhiteboardModal" tabindex="-1" role="dialog"
            aria-labelledby="whiteboardLabel" aria-modal="true" data-bs-theme="dark">
            <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="whiteboardLabel">{{ __('Whiteboard') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="whiteboardSection"></div>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-primary ms-auto" data-bs-dismiss="modal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-circle-check">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                <path d="M9 12l2 2l4 -4" />
                            </svg>
                            {{ __('Done') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- whiteboard modal end here -->

        <div id="overlay">
            <div class="overlay-wrapper">
                <p id="overlayText"></p>
                <img src="/images/allow.png" alt="{{ __('Allow Camera') }}" loading="lazy" />
            </div>
        </div>

        <div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1050;"></div>
        <input type="hidden" id="siteName" value="{{ getSetting('APPLICATION_NAME') }}" />
@endsection

@section('script')
    <script type="text/javascript">
        const userInfo = {
            username: htmlEscape(username.value),
            meetingId: "{{ $meeting->meeting_id }}",
            avatar: "{{ getAuthUserInfo('avatar') }}"
        };

        //to prevent XSS vulnerability
        function htmlEscape(input) {
            return input
                .replace(/&/g, '&amp;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');
        }

        const passwordRequired = "{{ !!$meeting->password }}";
        const moderator = "{{ $meeting->isModerator }}";
        const meetingTitle = "{{ $meeting->title }}";
        const timeLimit = "{{ $meeting->timeLimit == -1 ? 9999 : $meeting->timeLimit }}";
        const userLimit = "{{ $meeting->userLimit == -1 ? 9999 : $meeting->userLimit }}";
        const features = JSON.parse("{{ json_encode($meeting->features) }}".replace(/&quot;/g, '"'));
        Object.freeze(features);

        const transcriptionKeySetting = "{{ getAddonSetting('TRANSCRIPTION_KEY') ?? null }}";
        window.emojiScriptUrl = "{{ asset('js/emoji.js') }}";
    </script>
    <script src="{{ asset('js/socket.io.min.js') }}"></script>
    <script src="{{ asset('js/easytimer.min.js') }}"></script>
    <script src="{{ asset('js/opentok-layout.min.js') }}"></script>
    <script src="{{ asset('js/meeting2.js') }}"></script>
    <script src="{{ asset('js/fix-webm-duration.min.js') }}"></script>
    <script src="{{ asset('js/meeting.js?version=') . getSetting('VERSION') }}"></script>

    @stack('meeting_transcript_js')
@endsection
