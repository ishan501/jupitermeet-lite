<form>
    <div class="row">
        <div class="col-lg-6">
            <div class="mb-3">
                <label class="form-label">{{ __('Title') }}</label>
                <input type="text" value="{{ $filters['title'] ? $filters['title'] : '' }}" name="title"
                    class="form-control" placeholder="{{ __('Title') }}">
            </div>
        </div>
        <div class="col-lg-6">
            <div class="mb-3">
                <label class="form-label">{{ __('Slug') }}</label>
                <input type="text" name="slug" value="{{ $filters['slug'] ? $filters['slug'] : '' }}"
                    class="form-control" placeholder="{{ __('Slug') }}">
            </div>
        </div>
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-end mt-4 gap-1">
                <a href="{{ route('admin.page') }}"><button type="submit"
                        class="btn btn-primary">{{ __('Search') }}</button></a>
                <a href="{{ route('admin.page') }}"><button type="button"
                        class="btn bg-danger text-light">{{ __('Reset') }}</button></a>
            </div>
        </div>
    </div>
</form>
