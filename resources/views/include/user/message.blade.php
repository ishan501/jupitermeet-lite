@if (session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
        {{ session('message') }}
    </div>
@endif

@if (session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" id="success-alert">
        {{ session('error') }}
    </div>
@endif
