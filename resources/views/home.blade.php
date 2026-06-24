@extends('layouts.welcome')

@section('title', __('Video Conferencing Platform'))
@section('description', __('Video Conferencing Platform'))

@section('home-content')
    <div class="page jm-homepage">
        @include('include.user.header')
        @include('landing-page.main')
        @if (getSetting('PWA') == 'enabled')
            @include('include.pwa-installation-modal')

            <script type="text/javascript">
                if ('serviceWorker' in navigator) {
                    navigator.serviceWorker.register('/serviceworker.js', {
                        scope: '.'
                    }).then(function(registration) {}, function(err) {});
                }
            </script>
        @endif
        @include('include.cookie')
        <!-- How To Host Video Meetings end here -->
        <!-- footer start here -->
        @include('include.user.footer')
        <!-- footer end here -->
    </div>
@endsection
