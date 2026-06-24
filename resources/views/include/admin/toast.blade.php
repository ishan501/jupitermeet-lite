<div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1050">
    <div class="toast fade hide 
        @if (session()->has('message') || session()->has('error')) show 
        @else @endif" id="toast-simple"
        role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false"
        @if (session()->has('message') || session()->has('error')) @else style="display: none;" @endif>
        <div class="toast-header">
            <strong class="me-auto">{{ getSetting('APPLICATION_NAME') }}</strong>
            <small>{{ __('Just Now') }}</small>
            <button type="button" class="ms-2 btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            {{ session('message') ? session('message') : session('error') }}
        </div>
    </div>
</div>
