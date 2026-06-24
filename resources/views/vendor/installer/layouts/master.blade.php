<!DOCTYPE html>
<html data-bs-theme-base="neutral" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ trans('installer_messages.title') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('/images/FAVICON.png') }}" sizes="16x16" />
    <link href="{{ asset('/css/tabler.min.css') }}" rel="stylesheet" />
    <style>
        [data-bs-theme-base="neutral"] {
            --tblr-gray-50: #fafafa;
            --tblr-gray-100: #f5f5f5;
            --tblr-gray-200: #e5e5e5;
            --tblr-gray-300: #d4d4d4;
            --tblr-gray-400: #a3a3a3;
            --tblr-gray-500: #737373;
            --tblr-gray-600: #525252;
            --tblr-gray-700: #404040;
            --tblr-gray-800: #262626;
            --tblr-gray-900: #171717;
            --tblr-gray-950: #0a0a0a;
        }

        :root {
            --tblr-primary: #06ABD7 !important;
            --tblr-primary-rgb: 116, 83, 240 !important;
        }

        .form-control:focus {
            border-color: #06ABD7 !important;
        }

        .navbar-brand-image {
            height: 3rem;
        }
    </style>
</head>

<body>
    <div class="page page-center">
        <div class="container container-tight py-4">
            <div class="text-center mb-4">
                <img src="{{ asset('/images/PRIMARY_LOGO_WHITE.png') }}" height="80" width="80" alt="JupiterMeet" loading="lazy"
                    class="navbar-brand-image">
            </div>

            @yield('container')

        </div>
    </div>
</body>

</html>
