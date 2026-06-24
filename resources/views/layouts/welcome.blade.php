<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ getSelectedLanguage()->direction }}"
    data-bs-theme-base="neutral" data-bs-theme="{{ getThemeFromSession() }}">

<head>
    @include('include.layouts.common.head')
    <meta name="description" content="@yield('description')" />
    @if (getSetting('LANDING_PAGE') == 'enabled')
        <link href="{{ asset('/css/tabler-marketing.min.css') }}" rel="stylesheet" />
    @endif
    <link href="{{ asset('/css/home.css?version=') . getVersion() }}" rel="stylesheet" />
</head>

<body>
    @include('include.user.toast')
    @yield('home-content')

    @include('include.layouts.common.body')

    <script>
        const cookieConsent = "{{ getSetting('COOKIE_CONSENT') }}";
        const socialInvitation = `{{ getSetting('SOCIAL_INVITATION') }}`;
        const languages = {
            no_meeting: "{{ __('The meeting does not exist') }}",
        }
        const authUser = "{{ auth()->user() ? true : false }}";
    </script>


    <script src="{{ asset('/js/main.js?version=') . getVersion() }}"></script>
</body>

</html>
