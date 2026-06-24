<form>
    <div class="row">
        <div class="col-lg-6">
            <div class="mb-3">
                <label class="form-label">{{ __('Name') }}</label>
                <input type="text" value="{{ $filters['name'] ? $filters['name'] : '' }}" name="name"
                    class="form-control" placeholder="Name">
            </div>
        </div>
        <div class="col-lg-6">
            <div class="mb-3">
                <label class="form-label">{{ __('Description') }}</label>
                <input type="text" value="{{ $filters['description'] ? $filters['description'] : '' }}"
                    name="description" class="form-control" placeholder="Description">
            </div>
        </div>
        <div class="col-lg-6">
            <div class="mb-3">
                <label class="form-label">{{ __('Currency') }}</label>
                <select name="currency" class="form-select">
                    <option value="">{{ __('Please Select Option') }}</option>
                    @foreach ($currencies as $currency)
                        <option value="{{ $currency->code }}" @selected($filters['currency'] == $currency->code)>
                            {{ $currency->name }} - {{ $currency->code }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="mb-3">
                <label class="form-label">{{ __('Status') }}</label>
                <select name="status" class="form-select" aria-label="Status" placeholder="Select Option">
                    <option value="" selected>{{ __('Select Option') }}</option>
                    <option value="1" @selected($filters['status'] == 1)>{{ __('Active') }}</option>
                    <option value="2" @selected($filters['status'] == 2)>{{ __('Inactive') }}</option>
                </select>
            </div>
        </div>
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-end mt-4 gap-1">
                <a href="{{ route('admin.plan') }}"><button type="submit"
                        class="btn btn-primary">{{ __('Search') }}</button></a>
                <a href="{{ route('admin.plan') }}"><button type="button"
                        class="btn bg-danger text-light">{{ __('Reset') }}</button></a>
            </div>
        </div>
    </div>
</form>
