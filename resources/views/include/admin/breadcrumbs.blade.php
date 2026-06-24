<div class="mb-3 overflow-hidden position-relative">
    <div class="px-3">
        <h4 class="fs-3 mb-1">{{ $pageTitle }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                </li>
                @if (isset($module))
                    <li class="breadcrumb-item" aria-current="page"> <a
                            href="{{ route('admin.' . $module . '') }}">{{ ucFirst($module) . 's'   }}</a></li>
                @endif
                <li class="breadcrumb-item" aria-current="page">{{ $pageTitle }}</li>
            </ol>
        </nav>
    </div>
</div>
