<div class="col-12 col-md-3 border-end profile-tabs">
    <div class="card-body">
        <div class="list-group list-group-transparent">
            <a class="list-group-item list-group-item-action d-flex align-items-center {{ Route::currentRouteName() == 'user.profile.basic' ? 'active' : '' }}"
                href="{{ route('user.profile.basic') }}">{{ __('Basic Information') }}</a>
            <a class="list-group-item list-group-item-action d-flex align-items-center {{ Route::currentRouteName() == 'user.profile.security' ? 'active' : '' }}"
                href="{{ route('user.profile.security') }}">{{ __('Security') }}</a>
            @if (Route::has('pricing') && count(paymentGateways()) != 0 && getSetting('PAYMENT_MODE') == 'enabled')

                <a class="list-group-item list-group-item-action d-flex align-items-center {{ Route::currentRouteName() == 'user.profile.plan' ? 'active' : '' }}"
                    href="{{ route('user.profile.plan') }}">{{ __('My Plan') }}<svg xmlns="http://www.w3.org/2000/svg"
                        width="16" height="16" fill="currentColor" class="bi bi-gem ms-2" viewBox="0 0 16 16">
                        <path
                            d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
                    </svg></a>
                <a class="list-group-item list-group-item-action d-flex align-items-center {{ Route::currentRouteName() == 'user.profile.payment' ? 'active' : '' }}"
                    href="{{ route('user.profile.payment') }}">{{ __('Payments') }}<svg xmlns="http://www.w3.org/2000/svg"
                        width="16" height="16" fill="currentColor" class="bi bi-gem ms-2" viewBox="0 0 16 16">
                        <path
                            d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
                    </svg></a>
            @endif
            <a class="list-group-item list-group-item-action d-flex align-items-center {{ Route::currentRouteName() == 'user.profile.api-token' ? 'active' : '' }}"
                href="{{ route('user.profile.api-token') }}">{{ __('API Tokens') }}
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-gem ms-2" viewBox="0 0 16 16">
                    <path
                        d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
                </svg></a>
            <a class="list-group-item list-group-item-action d-flex align-items-center {{ Route::currentRouteName() == 'user.profile.contacts' || Route::currentRouteName() == 'user.profile.contact.create' || Route::currentRouteName() == 'user.profile.contact.edit' ? 'active' : '' }}"
                href="{{ route('user.profile.contacts') }}">{{ __('Contacts') }}
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-gem ms-2" viewBox="0 0 16 16">
                    <path
                        d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
                </svg></a>
            <a class="list-group-item list-group-item-action d-flex align-items-center {{ Route::currentRouteName() == 'user.profile.tfa' ? 'active' : '' }}"
                href="{{ route('user.profile.tfa') }}">{{ __('Two Factor Authentication') }}</a>
            @if (Auth::user()->role != 'admin')
                <a class="list-group-item list-group-item-action d-flex align-items-center {{ Route::currentRouteName() == 'user.profile.delete-account' ? 'active' : '' }}"
                    href="{{ route('user.profile.delete-account') }}">{{ __('Delete Account') }}</a>
            @endif
        </div>
    </div>
</div>