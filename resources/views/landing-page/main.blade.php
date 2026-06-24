<!-- meeting-join start here -->
<div class="jm-meeting-join">
    <div class="row g-0 flex-fill">
        <div class="col-12 col-lg-6 col-sm-6 col-xl-6">
            <!-- Photo -->
            <div class="jm-home-bg bg-cover h-100 min-vh-100 d-flex align-items-center justify-content-center"
                style="background-image: url(/images/background.svg)">
                <h2 class="w-100 p-4 text-center">{!! $page->content !!}</h2>
            </div>

        </div>
        <div class="col-12 col-lg-6 col-sm-6 col-xl-6 d-flex flex-column justify-content-center position-relative">
            <div class="container container-tight my-5 px-lg-5">
                <div class="jm-join-meeting justify-content-center">
                    <form id="meetingDashboard" class="w-100">
                        <div class="input-icon d-flex w-100">
                            <span class="input-icon-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-keyboard">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path
                                        d="M2 6m0 2a2 2 0 0 1 2 -2h16a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-16a2 2 0 0 1 -2 -2z">
                                    </path>
                                    <path d="M6 10l0 .01"></path>
                                    <path d="M10 10l0 .01"></path>
                                    <path d="M14 10l0 .01"></path>
                                    <path d="M18 10l0 .01"></path>
                                    <path d="M6 14l0 .01"></path>
                                    <path d="M18 14l0 .01"></path>
                                    <path d="M10 14l4 .01"></path>
                                </svg>
                            </span>
                            <input type="text" class="form-control" maxlength="9" id="conferenceId" name="id"
                                placeholder="{{ __('Enter Meeting ID') }}" required>
                            <button type="submit" class="link jm-join-link border-0">{{ __('JOIN') }}</button>
                        </div>
                    </form>
                </div>
                @if (getSetting('AUTH_MODE') == 'enabled')
                    <div class="text-secondary mt-3 d-flex justify-content-end">
                        <a class="link" href="{{ route('dashboard') }}" tabindex="-1">{{ __('Host a meeting?') }}</a>
                    </div>
                @endif
            </div>
            <div class="position-absolute bottom-0 start-50 translate-middle-x mb-4 w-100 text-center jm-promo-container-home"></div>
        </div>
    </div>
</div>
<!-- meeting-join end here -->
