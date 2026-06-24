<div class="card-header justify-content-between">
    <h3 class="font-weight-bold mb-3">{{ __('Profile') }}</h3>

          <!-- instant meeting & join meeting start here -->
          <div class="col-12 col-lg-7 col-xl-7">
            <div
                class="jm-header-right d-flex align-items-center justify-content-end flex-column flex-md-row">
                <!-- instant meeting start here-->
                <div
                    class="jm-instant-meeting d-flex align-items-center justify-content-between justify-content-md-start">
                    <h4 class="m-0">{{ __('Personal Meeting') }}</h4>
                    <div class="jm-right d-flex">
                        <a onclick="location.href='{{ route('meeting', ['id' => getAuthUserInfo('username')]) }}'"
                            class="btn btn-outline-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-send me-0 me-sm-2">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M10 14l11 -11" />
                                <path
                                    d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" />
                            </svg>
                            {{ __('Start') }}
                        </a>
                        <a id="copyMeetingLink" class="btn btn-outline-primary"
                            data-link="{{ route('meeting', ['id' => getAuthUserInfo('username')]) }}">
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
                    </div>
                </div>
                <!-- instant meeting emd here-->
            </div>
        </div>
        <!-- instant meeting & join meeting end here -->
</div>