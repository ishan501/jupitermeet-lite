 <meta charset="utf-8" />
 <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
 <meta http-equiv="X-UA-Compatible" content="ie=edge" />
 <meta name="csrf-token" content="{{ csrf_token() }}">

 @if (str_contains(getSetting('APPLICATION_NAME'), 'JupiterMeet'))
     <meta name="robots" content="noindex, nofollow, noarchive">
 @endif

 <title translate="no">{{ getSetting('APPLICATION_NAME') . ' - ' }} @yield('title')</title>
 <link rel="icon" type="image/png" href="{{ asset('storage/images/' . getSetting('FAVICON')) }}">

 <style>
     :root {
         --tblr-primary: {{ getSetting('PRIMARY_COLOR') }} !important;
     }
 </style>

 @if (getSelectedLanguage()->direction === 'rtl')
     <link href="{{ asset('/css/tabler.rtl.min.css') }}" rel="stylesheet" />
 @else
     <link href="{{ asset('/css/tabler.min.css') }}" rel="stylesheet" />
 @endif

 @if (getSetting('PWA') == 'enabled')
     <link rel="manifest" href="/manifest.json">
 @endif

 <style>
     @font-face {
         font-family: Inter;
         font-style: normal;
         font-weight: 400;
         font-display: swap;
         src: url("/css/font-files/Inter-Regular.woff2?v=4.1") format("woff2");
     }
 </style>
