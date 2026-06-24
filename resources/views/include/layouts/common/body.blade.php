<div id="cover-spin"></div>

@if (getSetting('PWA') == 'enabled')
    @include('include.pwa-installation-modal')

    <script type="text/javascript">
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/serviceworker.js', {
                scope: '.'
            }).then(function (registration) { }, function (err) { });
        }
    </script>
@endif

<script src="{{ asset('/js/tabler.min.js') }}"></script>
<script src="{{ asset('/js/jquery.min.js') }}"></script>
<script src="{{ asset('/js/system.js') }}"></script>

<script>
    const pwa = "{{ getSetting('PWA') }}";
</script>