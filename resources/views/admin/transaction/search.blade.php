    <form>
        <div class="row">
            <div class="col-lg-6">
                <div class="mb-3">
                    <label class="form-label">{{ __('Username') }}</label>
                    <input type="text" value="{{ $username ? $username : '' }}" name="username" class="form-control"
                        placeholder="{{ __('Username') }}" maxlength="20">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-3">
                    <label class="form-label">{{ __('Plan') }}</label>
                    <input type="text" name="plan" value="{{ $plan ? $plan : '' }}" class="form-control"
                        placeholder="{{ __('Plan') }}" maxlength="255">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-3">
                    <label class="form-label">{{ __('Coupon') }}</label>
                    <input type="text" value="{{ $filters['coupon'] ? $filters['coupon'] : '' }}" name="coupon"
                        class="form-control" placeholder="{{ __('Coupon') }}" maxlength="255">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-3">
                    <label class="form-label">{{ __('Type') }}</label>
                    <input type="text" name="type" value="{{ $filters['interval'] ? $filters['interval'] : '' }}"
                        class="form-control" placeholder="{{ __('Type') }}">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-3">
                    <label class="form-label">{{ __('Payment Gateway') }}</label>
                    <input type="text" value="{{ $filters['gateway'] ? $filters['gateway'] : '' }}"
                        name="payment_gateway" class="form-control" placeholder="{{ __('Payment gateway') }}">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-3">
                    <label class="form-label">{{ __('Transaction ID') }}</label>
                    <input type="text" name="transaction_id"
                        value="{{ $filters['payment_id'] ? $filters['payment_id'] : '' }}" class="form-control"
                        placeholder="{{ __('Transaction ID') }}">
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-end mt-4 gap-1">
                    <a href="{{ route('admin.transaction') }}"><button type="submit"
                            class="btn btn-primary">{{ __('Search') }}</button></a>
                    <a href="{{ route('admin.transaction') }}"><button type="button"
                            class="btn bg-danger text-light">{{ __('Reset') }}</button></a>
                </div>
            </div>
        </div>
    </form>
