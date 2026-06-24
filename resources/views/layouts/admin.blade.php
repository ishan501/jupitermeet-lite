<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ getSelectedLanguage()->direction }}"
    data-bs-theme-base="neutral" data-bs-theme="{{ getThemeFromSession() }}">

<head>
    @include('include.layouts.common.head')

    <style>
        :root {
            --tblr-primary: #06ABD7 !important;
            --tblr-primary-rgb: 116, 83, 240 !important;
        }
        
    </style>
    <link href="{{ asset('/css/admin.css?version=') . getVersion() }}" rel="stylesheet" />
    <link href="{{ asset('/css/quill.snow.css') }}" rel="stylesheet">

    @yield('styles')
</head>

<body>
    <div class="page">
        @include('include.admin.sidebar')
        @include('include.admin.header')
        <div class="page-wrapper">
            @yield('content')
            @include('include.admin.footer')
        </div>
    </div>

    @include('include.layouts.common.body')

    <script>
        const translations = {
            paid_pie_chart: "{{ __('Paid') }}",
            free_pie_chart: "{{ __('Free') }}",
            jan: "{{ __('Jan') }}",
            feb: "{{ __('Feb') }}",
            mar: "{{ __('Mar') }}",
            apr: "{{ __('Apr') }}",
            may: "{{ __('May') }}",
            jun: "{{ __('June') }}",
            jul: "{{ __('Jul') }}",
            aug: "{{ __('Aug') }}",
            sep: "{{ __('Sep') }}",
            oct: "{{ __('Oct') }}",
            nov: "{{ __('Nov') }}",
            dec: "{{ __('Dec') }}",
            update_available: "{{ __('An update is available: Version: ') }}",
            already_latest_version: "{{ __('Application is already at latest version. Version: ') }}",
            application_updated: "{{ __('The application has been successfully updated to the latest version') }}",
            update_failed: "{{ __('Update failed. Error: ') }}",
            valid_license: "{{ __('Your license is valid. Type: ') }}",
            invalid_license: "{{ __('Your license is invalid. Error: ') }}",
            license_uninstalled: "{{ __('License uninstalled') }}",
            license_uninstalled_failed: "{{ __('License uninstallation failed. Error: ') }}",
            copied_to_clipboard: "{{ __('Copied to clipboard.') }}",
            monthly_income: "{{ __('Monthly Income') }}",
            monthly_user_registered: "{{ __('Monthly User Registered') }}",
            monthly_meetings: "{{ __('Monthly Meetings') }}",
            license_confirmation: "{{ __('Are you sure you want to uninstall the license?') }}"
        }
    </script>

    @yield('script')

    <script src="{{ asset('/js/admin.js?version=') . getVersion() }}"></script>
</body>

</html>
