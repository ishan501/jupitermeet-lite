<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ getSelectedLanguage()->direction }}"
    data-bs-theme-base="neutral" data-bs-theme="{{ getThemeFromSession() }}">

<head>
    @include('include.layouts.common.head')

    <link href="{{ asset('/css/custom.css?version=') . getVersion() }}" rel="stylesheet" />

    @yield('styles')
</head>

<body>
    @yield('content')
    @include('include.cookie')
    @include('include.user.footer')

    @include('include.layouts.common.body')

    <script>
        const cookieConsent = "{{ getSetting('COOKIE_CONSENT') }}";
        const socialInvitation = `{{ getSetting('SOCIAL_INVITATION') }}`;

        const languages = {
            error_occurred: "{{ __('An error occurred, please try again') }}",
            data_updated: "{{ __('Data updated successfully') }}",
            no_meeting: "{{ __('The meeting does not exist') }}",
            meeting_created: "{{ __('The meeting has been created') }}",
            confirmation: "{{ __('Are you sure?') }}",
            meeting_deleted: "{{ __('The meeting has been deleted') }}",
            link_copied: "{{ __('Meeting link has been copied to the clipboard') }}",
            meeting_updated: "{{ __('The meeting has been updated') }}",
            sending_invite: "{{ __('Sending the invitation') }}",
            inviteMessage: "{{ __('Hey there! Join me for a meeting at this link') }}",
            no_session: "{{ __('Could not get the session details') }}",
            kicked: "{{ __('You have been kicked out of the meeting') }}",
            uploading: "{{ __('Uploading the file') }}",
            meeting_ended: "{{ __('Meeting ended') }}",
            cant_connect: "{{ __('Could not connect to the server, please try again later') }}",
            invalid_password: "{{ __('The password is invalid') }}",
            no_device: "{{ __('Could not get the devices, please check the permissions and try again. Error') }}",
            approve: "{{ __('Approve') }}",
            decline: "{{ __('Decline') }}",
            request_join_meeting: "{{ __('Request to join the meeting') }}",
            request_declined: "{{ __('Your request has been declined by the moderator') }}",
            double_click: "{{ __('Double click on the video to make it fullscreen') }}",
            single_click: "{{ __('Single click on the video to turn picture-in-picture mode on') }}",
            error_message: "{{ __('An error occurred') }}",
            kick_user: "{{ __('Kick this user') }}",
            participant_joined: "{{ __('A participant has joined the meeting') }}",
            confirmation_kick: "{{ __('Are you sure you want to kick this user') }}",
            participant_left: "{{ __('A participant has left the meeting') }}",
            camera_on: "{{ __('Camera has been turned on') }}",
            camera_off: "{{ __('Camera has been turned off') }}",
            mic_unmute: "{{ __('Mic has been unmute') }}",
            mic_mute: "{{ __('Mic has been muted') }}",
            no_video: "{{ __('The video is not playing or has no video track') }}",
            no_pip: "{{ __('Picture-in-picture mode is not supported in this browser') }}",
            link_copied: "{{ __('The meeting invitation link has been copied to the clipboard') }}",
            cant_share_screen: "{{ __('Could not share the screen, please check the permissions and try again') }}",
            max_file_size: "{{ __('Maximum file size allowed (MB)') }}",
            view_file: "{{ __('View File') }}",
            hand_raised: "{{ __('Hand raised') }}",
            hand_raised_self: "{{ __('You raised hand') }}",
            your_screen: "{{ __('Your screen') }}",
            not_started: "{{ __('The meeting has not been started yet') }}",
            meeting_full: "{{ __('The meeting is full') }}",
            please_wait: "{{ __('Please wait while the moderator check your request') }}",
            system_error: "{{ __('System is not properly configured. Please contact the administrator.') }}", 
            request_record_meeting: "{{ __('Request to record the meeting') }}",
            request_screenshare: "{{ __('Request to start screen sharing') }}",
            record_request_declined: "{{ __('Your recording request was not approved') }}",
            screenshare_request_declined: "{{ __('Your screen share request was not approved') }}",
            feature_not_supported: "{{ __('This feature is not yet supported in your browser') }}",
            feature_not_available: "{{ __('This feature is not available in the current meeting plan') }}",
            password: "{{ __('Password: ') }}",
            calendar_check: "{{ __('Please set a date and time') }}",
            recording_started: "{{ __('The recording has been started') }}",
            recording_stopped: "{{ __('The recording has been stopped') }}",
            token_copied: "{{ __('API Token has been copied to the clipboard') }}",
            screen: "{{ __('Screen-') }}",
            checking_mic_permission: "{{ __('Checking microphone permission') }}",
            checking_cam_permission: "{{ __('Checking camera permission') }}",
            click_allow: "{{ __('Click \"Allow\"') }}",
            personal_link_copied: "{{ __('Your personal meeting link has been copied to the clipboard') }}",
            you_muted: "{{ __('You muted all the participants') }}",
            you_unmuted: "{{ __('You unmuted all the participants') }}",
            mic_muted_moderator: "{{ __('Mic has been muted by the moderator') }}",
            camera_off_moderator: "{{ __('Camera has been turned off by the moderator') }}",
            moderator: "{{ __('Moderator') }}",
            moderator_updated: "{{ __('The moderator has been updated. New moderator: ') }}",
            make_moderator: "{{ __('Make Moderator') }}",
            you_moderator: "{{ __('You are now the moderator') }}",
            moderator_confirm: "{{ __('Are you sure you want to switch the moderator right? This action can not be undone') }}",
            api_token_copied: "{{ __('API Token copied successfully.') }}",
            copied_text: "{{ __('Copied') }}",
            embed_code: "{{ __('Embed Code') }}",
            copy_link: "{{ __('Copy link') }}",
            just_now: "{{ __('Just Now') }}",
            online: "{{ __('Back online') }}",
            offline: "{{ __('You are offline') }}",
            turn_off_mic: "{{ __('Turn off mic') }}",
            turn_off_cam: "{{ __('Turn off cam') }}",
            mute_all: "{{ __('Mute All') }}",
            unmute_all: "{{ __('Unmute All') }}",
            meeting_ending: "{{ __('The meeting will end in one minute') }}",
            message_deepSeek: "{{ __('Message DeepSeek') }}",
            message_gemini: "{{ __('Message Gemini') }}",
            message_perplexity: "{{ __('Message Perplexity') }}",
            message_grok: "{{ __('Message Grok') }}",
            you: "{{ __('You') }}",
            start: "{{ __('Start') }}",
            filesize_limit_message: "{{ __('The uploaded file exceeds the maximum allowed size.') }}",
            premiumFeature: "{{ __('This is a premium feature. User\'s current plan does not support this feature.') }}",
            loading_details: "{{ __('Loading session details') }}",
            unable_loading_details: "{{ __('Unable to load session details.') }}",
            error_loading_details: "{{ __('Something went wrong while loading session details.') }}",

        }
        const authUser = "{{ auth()->user() ? true : false }}";
    </script>

    <script src="{{ asset('/js/main.js?version=') . getVersion() }}"></script>
    @yield('script')
</body>

</html>
