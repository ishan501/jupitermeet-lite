 <!-- toast start here -->
 <div class="toast-container position-fixed top-69 end-0 p-3">
    <div class="toast fade hide @if (session('message')) show @endif" id="toast-simple" role="alert"
        aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
        <div class="toast-header">
            <strong class="me-auto">{{ getSetting('APPLICATION_NAME') }}</strong>
            <small>{{ __('Just Now') }}</small>
            <button type="button" class="ms-2 btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            {{ session('message') ? session('message') : '' }}
        </div>
    </div>
</div>