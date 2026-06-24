@extends('layouts.admin')
@section('title', $pageTitle)

@section('styles')
    <link href="{{ asset('/css/quill.snow.css') }}" rel="stylesheet">
@endsection

@section('content')
    @include('include.admin.toast')

    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    @include('include.admin.breadcrumbs', [
                        'module' => __('email-template'),
                    ])
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-body">
                    <form id="emailTemplateForm" action="{{ route('admin.email-template.update', $emailTemplate->id) }}"
                        method="post">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div>
                                    <label class="form-label">{{ __('Name') }}</label>
                                    <input type="text" name="name" placeholder="{{ __('Name') }}"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ $emailTemplate->name }}" maxlength="64" autofocus>
                                    @error('name')
                                        <small class="invalid-feedback">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Slug') }}</label>
                                    <input type="text" name="slug" placeholder="{{ __('Slug') }}"
                                        class="form-control @error('slug') is-invalid @enderror"
                                        value="{{ $emailTemplate->slug }}" maxlength="255" readonly disabled>
                                    @error('slug')
                                        <small class="invalid-feedback">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label class="form-label">{{ __('Content') }}</label>

                            @if (!empty($variables))
                                <div class="d-flex mb-1">
                                    <div class="flex-wrap gap-2">
                                        @foreach ($variables as $variable)
                                            <button type="button" class="btn btn-sm btn-outline-primary copy-variable ms-1"
                                                title="{{ __('Click to copy') }}"
                                                data-variable="{{ $variable }}">{{ $variable }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            
                            <div class="card mt-2">
                                <div class="card-body p-0">
                                    <div class="d-block">
                                        <div id="emailTemplateContentEditor" class="bg-transparent border-0 h-350px px-3">
                                            {!! old('content') ?? $emailTemplate->content !!}
                                        </div>
                                        <input type="hidden" id="content" name="content"
                                            value="{{ old('content') ?? $emailTemplate->content }}">
                                        @error('content')
                                            <small class="invalid-feedback">
                                                {{ $message }}
                                            </small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-md-flex align-items-center">
                                <div class="ms-auto mt-3 mt-md-0">
                                    <a href="{{ route('admin.email-template') }}"><button type="button"
                                            class="btn btn-default">{{ __('Back') }}</button></a>
                                </div>
                                <div class="ms-2 mt-3 mt-md-0">
                                    <button type="submit" name="save"
                                        class="btn btn-primary">{{ __('Save') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('/js/quill.min.js') }}"></script>
@endsection
