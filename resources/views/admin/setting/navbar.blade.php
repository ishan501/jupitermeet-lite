<div class="col-12 col-md-3 border-end">
    <div class="card-body">
        <div class="list-group list-group-transparent">
            <a class="list-group-item list-group-item-action d-flex align-items-center {{ Route::currentRouteName() == 'admin.setting' ? 'active' : '' }}"
                href="{{ route('admin.setting') }}">{{ __('Basic') }}</a>
            <a class="list-group-item list-group-item-action d-flex align-items-center {{ Route::currentRouteName() == 'admin.setting.application' ? 'active' : '' }}"
                href="{{ route('admin.setting.application') }}">{{ __('Application') }}</a>
            <a class="list-group-item list-group-item-action d-flex align-items-center {{ Route::currentRouteName() == 'admin.setting.meeting' ? 'active' : '' }}"
                href="{{ route('admin.setting.meeting') }}">{{ __('Meeting') }}</a>
            <a class="list-group-item list-group-item-action d-flex align-items-center {{ Route::currentRouteName() == 'admin.setting.nodejs' ? 'active' : '' }}"
                href="{{ route('admin.setting.nodejs') }}">{{ __('Signaling') }}</a>
            <a class="list-group-item list-group-item-action d-flex align-items-center {{ Route::currentRouteName() == 'admin.setting.aichatbot' ? 'active' : '' }}"
                href="{{ route('admin.setting.aichatbot') }}">{{ __('AI Chatbot') }}<svg
                    xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gem ms-2"
                    viewBox="0 0 16 16">
                    <path
                        d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
                </svg></a>
            <a class="list-group-item list-group-item-action d-flex align-items-center {{ Route::currentRouteName() == 'admin.setting.custom-js' ? 'active' : '' }}"
                href="{{ route('admin.setting.custom-js') }}">{{ __('Custom JS') }}<svg
                    xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gem ms-2"
                    viewBox="0 0 16 16">
                    <path
                        d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
                </svg></a>
            <a class="list-group-item list-group-item-action d-flex align-items-center {{ Route::currentRouteName() == 'admin.setting.custom-css' ? 'active' : '' }}"
                href="{{ route('admin.setting.custom-css') }}">{{ __('Custom CSS') }}<svg
                    xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gem ms-2"
                    viewBox="0 0 16 16">
                    <path
                        d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
                </svg></a>
            <a class="list-group-item list-group-item-action d-flex align-items-center {{ Route::currentRouteName() == 'admin.setting.smtp' ? 'active' : '' }}"
                href="{{ route('admin.setting.smtp') }}">{{ __('SMTP') }}</a>
            <a class="list-group-item list-group-item-action d-flex align-items-center {{ Route::currentRouteName() == 'admin.setting.google-recaptcha' ? 'active' : '' }}"
                href="{{ route('admin.setting.google-recaptcha') }}">{{ __('Google reCAPTCHA') }}<svg
                    xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gem ms-2"
                    viewBox="0 0 16 16">
                    <path
                        d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
                </svg></a>
            <a class="list-group-item list-group-item-action d-flex align-items-center {{ Route::currentRouteName() == 'admin.setting.company-information' ? 'active' : '' }}"
                href="{{ route('admin.setting.company-information') }}">{{ __('Company Information') }}<svg
                    xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gem ms-2"
                    viewBox="0 0 16 16">
                    <path
                        d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
                </svg></a>
            <a class="list-group-item list-group-item-action d-flex align-items-center {{ Route::currentRouteName() == 'admin.setting.social-login' ? 'active' : '' }}"
                href="{{ route('admin.setting.social-login') }}">{{ __('Social Login') }} <svg
                    xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gem ms-2"
                    viewBox="0 0 16 16">
                    <path
                        d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
                </svg></a>
        </div>
    </div>
</div>