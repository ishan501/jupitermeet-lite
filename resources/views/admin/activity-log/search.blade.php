<form>
    <div class="row">
        <div class="col-lg-6">
            <div class="mb-3">
                <label class="form-label">{{ __('Username') }}</label>
                <input type="text" value="{{ $username ? $username : '' }}" name="username" class="form-control"
                    placeholder="{{ __('Username') }}">
            </div>
        </div>

        <div class="col-lg-6">
            <div class="mb-3">
                <label class="form-label">{{ __('Module') }}</label>
                @php
                    $modulesArray = ['User', 'Meeting', 'Plan', 'Coupon', 'TaxRate', 'Settting', 'Contact', 'Payment'];
                @endphp
                <select name="module" class="form-select" aria-label="Module" placeholder="Select Option">
                    <option value="">{{ __('Please Select Option') }}</option>
                    @foreach ($modulesArray as $val)
                        <option value="{{ $val }}" @selected(isset($filters['subject_type']) && $filters['subject_type'] == $val)>
                            {{ __($val) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="mb-3">
                <label class="form-label">{{ __('Event') }}</label>
                @php
                    $eventArray = ['created', 'updated', 'deleted', 'Logged In', 'Logged Out', 'Registered'];
                @endphp
                <select name="event" class="form-select" aria-label="Event" placeholder="{{ __('Select Option') }}">
                    <option value="">{{ __('Please Select Option') }}</option>
                    @foreach ($eventArray as $val)
                        <option value="{{ $val }}" @selected(isset($filters['event']) && $filters['event'] == $val)>
                            {{ __($val) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="mb-3">
                <label for="date" class="form-label">{{ __('Created Date') }}</label>
                <div class="position-relative">
                    <!-- Input Field -->
                    <input id="litepicker" class="form-control ps-5 pe-5" name="created_date"
                        placeholder="Select a date" value="{{ $dateRange ? $dateRange : '' }}" />

                    <!-- Left Icon (Calendar) -->
                    <span class="position-absolute top-50 start-0 translate-middle-y ms-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round" class="icon text-muted">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" />
                            <path d="M16 3v4" />
                            <path d="M8 3v4" />
                            <path d="M4 11h16" />
                            <path d="M11 15h1" />
                            <path d="M12 15v3" />
                        </svg>
                    </span>

                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="position-absolute top-50 end-0 translate-middle-y me-2" id="clearDate"
                        viewBox="0 0 16 16">
                        <path
                            d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="mb-3">
                <label class="form-label">{{ __('IP') }}</label>
                <input type="text" name="ip" value="{{ $filters['ip'] ? $filters['ip'] : '' }}"
                    class="form-control" placeholder="{{ __('IP') }}">
            </div>
        </div>
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-end mt-4 gap-1">
                <a href="{{ route('admin.activity-log') }}"><button type="submit"
                        class="btn btn-primary">{{ __('Search') }}</button></a>
                <a href="{{ route('admin.activity-log') }}"><button type="button"
                        class="btn bg-danger text-light">{{ __('Reset') }}</button></a>
            </div>
        </div>
    </div>
</form>
