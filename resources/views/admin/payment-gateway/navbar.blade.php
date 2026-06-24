{{-- s<ul class="nav nav-tabs d-flex flex-fill flex-column flex-md-row mb-3" id="pills-tab" role="tablist">
    <li class="nav-item text-center">
        @if (!$errors->has('STRIPE_KEY') && !$errors->has('STRIPE_SECRET') && !$errors->has('STRIPE_WH_SECRET') && ($errors->has('PAYPAL_CLIENT_ID') || $errors->has('PAYPAL_SECRET') || $errors->has('PAYPAL_WEBHOOK_ID') || $errors->has('PAYSTACK_SECRET_KEY') || $errors->has('MOLLIE_API_KEY')))
            <a class="nav-link" href="{{ route('admin.payment-gateway.stripe') }}">{{ __('Stripe') }}</a>
        @else
            <a class="nav-link {{ Route::currentRouteName() == 'admin.payment-gateway.stripe' ? 'active' : '' }}"
                href="{{ route('admin.payment-gateway.stripe') }}">{{ __('Stripe') }}</a>
        @endif
    </li>

    <li class="nav-item text-center">
        @if (!$errors->has('STRIPE_KEY') && !$errors->has('STRIPE_SECRET') && !$errors->has('STRIPE_WH_SECRET') && !$errors->has('PAYSTACK_SECRET_KEY') && !$errors->has('MOLLIE_API_KEY') && ($errors->has('PAYPAL_CLIENT_ID') || $errors->has('PAYPAL_SECRET') || $errors->has('PAYPAL_WEBHOOK_ID')))
            <a class="nav-link" href="{{ route('admin.payment-gateway.paypal') }}">{{ __('PayPal') }}</a>
        @else
            <a class="nav-link {{ Route::currentRouteName() == 'admin.payment-gateway.paypal' ? 'active' : '' }}"
                href="{{ route('admin.payment-gateway.paypal') }}">{{ __('PayPal') }}</a>
        @endif
    </li>

    <li class="nav-item text-center">
        @if (!$errors->has('STRIPE_KEY') && !$errors->has('STRIPE_SECRET') && !$errors->has('STRIPE_WH_SECRET') && !$errors->has('PAYPAL_CLIENT_ID') && !$errors->has('PAYPAL_SECRET') && !$errors->has('PAYPAL_WEBHOOK_ID') && $errors->has('PAYSTACK_SECRET_KEY') && !$errors->has('MOLLIE_API_KEY'))
            <a class="nav-link" href="{{ route('admin.payment-gateway.paystack') }}">{{ __('Paystack') }}</a>
        @else
            <a class="nav-link {{ Route::currentRouteName() == 'admin.payment-gateway.paystack' ? 'active' : '' }}"
                href="{{ route('admin.payment-gateway.paystack') }}">{{ __('Paystack') }}</a>
        @endif
    </li>

    <li class="nav-item text-center">
        @if (!$errors->has('STRIPE_KEY') && !$errors->has('STRIPE_SECRET') && !$errors->has('STRIPE_WH_SECRET') && !$errors->has('PAYPAL_CLIENT_ID') && !$errors->has('PAYPAL_SECRET') && !$errors->has('PAYPAL_WEBHOOK_ID') && !$errors->has('PAYSTACK_SECRET_KEY') && $errors->has('MOLLIE_API_KEY'))
            <a class="nav-link" href="{{ route('admin.payment-gateway.mollie') }}">{{ __('Mollie') }}</a>
        @else
            <a class="nav-link {{ Route::currentRouteName() == 'admin.payment-gateway.mollie' ? 'active' : '' }}"
                href="{{ route('admin.payment-gateway.mollie') }}">{{ __('Mollie') }}</a>
        @endif
    </li>

    <li class="nav-item text-center">
        @if (!$errors->has('STRIPE_KEY') && !$errors->has('STRIPE_SECRET') && !$errors->has('STRIPE_WH_SECRET') && !$errors->has('PAYPAL_CLIENT_ID') && !$errors->has('PAYPAL_SECRET') && !$errors->has('PAYPAL_WEBHOOK_ID') && !$errors->has('PAYSTACK_SECRET_KEY' && !$errors->has('MOLLIE_API_KEY')) && ($errors->has('RAZORPAY_API_KEY') || $errors->has('RAZORPAY_SECRET_KEY')))
            <a class="nav-link" href="{{ route('admin.payment-gateway.razorpay') }}">{{ __('Razorpay') }}</a>
        @else
            <a class="nav-link {{ Route::currentRouteName() == 'admin.payment-gateway.razorpay' ? 'active' : '' }}"
                href="{{ route('admin.payment-gateway.razorpay') }}">{{ __('Razorpay') }}</a>
        @endif
    </li>
</ul> --}}



<div class="col-12 col-md-2 border-end">
    <div class="card-body">
        <div class="list-group list-group-transparent">
            <a class="list-group-item list-group-item-action d-flex align-items-center {{ Route::currentRouteName() == 'admin.payment-gateway.stripe' ? 'active' : '' }}"
                href="{{ route('admin.payment-gateway.stripe') }}">{{ __('Stripe') }}</a>
            <a class="list-group-item list-group-item-action d-flex align-items-center {{ Route::currentRouteName() == 'admin.payment-gateway.paypal' ? 'active' : '' }}"
                href="{{ route('admin.payment-gateway.paypal') }}">{{ __('PayPal') }}</a>
            <a class="list-group-item list-group-item-action d-flex align-items-center {{ Route::currentRouteName() == 'admin.payment-gateway.paystack' ? 'active' : '' }}"
                href="{{ route('admin.payment-gateway.paystack') }}">{{ __('Paystack') }}</a>
            <a class="list-group-item list-group-item-action d-flex align-items-center {{ Route::currentRouteName() == 'admin.payment-gateway.mollie' ? 'active' : '' }}"
                href="{{ route('admin.payment-gateway.mollie') }}">{{ __('Mollie') }}</a>
            <a class="list-group-item list-group-item-action d-flex align-items-center {{ Route::currentRouteName() == 'admin.payment-gateway.razorpay' ? 'active' : '' }}"
                href="{{ route('admin.payment-gateway.razorpay') }}">{{ __('Razorpay') }}</a>
        </div>
    </div>
</div>
