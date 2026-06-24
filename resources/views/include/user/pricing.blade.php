<div class="container mt-3">
    <div class="row">
        <div class="col-12 col-lg-12 col-xl-12">
            <div class="d-flex justify-content-between flex-column text-center">
                <div class="jm-heading mb-3">
                    <h1 class="mb-3">{{ __('Simple, transparent pricing') }}</h1>
                    <p class="m-0">{{ __('Choose a plan that works best for you.') }}</p>
                </div>
                <div class="col-auto mb-2">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn" id="monthlyBtn">{{ __('Monthly') }}</button>
                        <button type="button" class="btn" id="yearlyBtn">{{ __('Yearly') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row row-cards d-flex justify-content-center mt-5">
        @foreach ($plans as $plan)
            <div class="col-lg-3">
                <div class="card card-md @if ($plan->popular == 'true') featured @endif ">
                    @if ($plan->popular == 'true')
                        <div class="pricing-label">
                            <div class="badge bg-primary text-primary-fg">{{ __('Popular') }}</div>
                        </div>
                    @endif
                    @if ($plan->amount_month * 12 > $plan->amount_year)
                        <div class="plan-year d-none">
                            <div class="ribbon ribbon-top ribbon-bookmark">
                                <h4 class="m-0">
                                    {{ __(':value%', ['value' => number_format((($plan->amount_month * 12 - $plan->amount_year) / ($plan->amount_month * 12)) * 100, 0)]) }}
                                </h4>
                            </div>
                        </div>
                    @endif
                    <div class="card-body text-center">
                        <div class="text-uppercase text-secondary font-weight-medium">{{ $plan->name }}</div>
                        <div class="plan-preload plan-month d-none d-block">
                            <div class="h1 mb-1">
                                @if (formatMoney($plan->amount_month, $plan->currency) == 0.0)
                                    <span class="display-5 fw-bold my-3 text-uppercase">{{ __('Free') }}</span>
                                @else
                                    <span class="display-5 fw-bold my-3">
                                        {{ formatMoney($plan->amount_month, $plan->currency) }}
                                    </span>
                                    <span class="pricing-plan-price text-muted">
                                        {{ $plan->currency }}
                                    </span>
                                @endif

                            </div>
                        </div>
                        <div class="plan-preload plan-year d-none d-block">
                            <div class="h1 mb-1">
                                @if (formatMoney($plan->amount_year, $plan->currency) == 0.0)
                                    <span class="display-5 fw-bold my-3 text-uppercase">{{ __('Free') }}</span>
                                @else
                                    <span class="display-5 fw-bold my-3">
                                        {{ formatMoney($plan->amount_year, $plan->currency) }}
                                    </span>
                                    <span class="pricing-plan-price text-muted">
                                        {{ $plan->currency }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <ul class="list-unstyled lh-lg">
                            @foreach ($plan->features as $feature => $enabled)
                                <li
                                    class="@if ($enabled == 0) text-decoration-line-through text-muted @endif p-1">
                                    @if ($feature == 'max_filesize')
                                        File sharing limit:<span class="fw-bold">
                                            {{ $enabled == '-1' ? 'Unlimited' : 'Up to    ' . $enabled . 'MB' }}</span>
                                    @elseif ($feature == 'video_quality')
                                        <span class="fw-bold"> {{ $enabled }}
                                        </span> Video quality
                                    @elseif ($feature == 'meeting_no')
                                        <span class="fw-bold"> {{ $enabled == '-1' ? 'Unlimited' : $enabled }}
                                        </span> Meeting(s)
                                    @elseif ($enabled == -1)
                                        <span class="fw-bold">{{ __('Unlimited') }}</span>
                                        {{ ucfirst(str_replace('_', ' ', $feature)) }}
                                    @elseif (is_numeric($enabled) && $enabled > 1)
                                        <span class="fw-bold">{{ __('Up to') }} {{ $enabled }}</span>
                                        {{ ucfirst(str_replace('_', ' ', $feature)) }}
                                    @else
                                        {{ ucfirst(str_replace('_', ' ', $feature)) }}
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                        <div class="text-center z-1 w-100">
                            @auth
                                @if ($plan->hasPrice() && count(paymentGateways()) != 0)
                                    @if (isset(getAuthUserInfo('plan')->id) &&
                                            getAuthUserInfo('plan')->id == $plan->id &&
                                            getAuthUserInfo('plan_recurring_at') != null)
                                        <div class="btn w-100 disabled">
                                            {{ __('Active') }}</div>
                                    @else
                                        <div class="plan-no-animation plan-month d-none d-block">
                                            <a href="{{ route('checkout.index', ['id' => $plan->id, 'interval' => 'month']) }}"
                                                class="btn w-100 py-2">
                                                {{ __('Subscribe') }}
                                            </a>
                                        </div>
                                        <div class="plan-no-animation plan-year d-none">
                                            <a href="{{ route('checkout.index', ['id' => $plan->id, 'interval' => 'year']) }}"
                                                class="btn w-100 py-2">
                                                {{ __('Subscribe') }}
                                            </a>
                                        </div>
                                    @endif
                                @else
                                    <div class="btn w-100 py-2 disabled">
                                        {{ __('Free') }}
                                    </div>
                                @endif
                            @else
                                <div class="plan-no-animation plan-month d-none d-block">
                                    <a href="{{ route('register', ['plan' => $plan->id, 'interval' => 'month']) }}"
                                        class="btn w-100 py-2">{{ __('Register') }}</a>
                                </div>
                                <div class="plan-no-animation plan-year d-none">
                                    <a href="{{ route('register', ['plan' => $plan->id, 'interval' => 'year']) }}"
                                        class="btn w-100 py-2">{{ __('Register') }}</a>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
