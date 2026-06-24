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
                        'module' => __('page'),
                    ])
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-body">
                    <form id="pageForm" action="{{ route('admin.page.update', $page->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div>
                                    <label class="form-label">{{ __('Title') }}</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        placeholder="Enter Title" name="title" minlength="3" maxlength='64'
                                        value="{{ old('title') ?? $page->title }}">
                                    @error('title')
                                        <small class="invalid-feedback">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div>
                                    <label class="form-label">{{ __('Slug') }}</label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                        placeholder="Enter Slug" name="slug" maxlength="64"
                                        value="{{ old('slug') ?? $page->slug }}"
                                        @if (in_array($page->slug, $restrictedSlugs)) readonly @endif>
                                    @error('slug')
                                        <small class="invalid-feedback">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-8 mb-3">
                                <label>{{ __('Content') }}</label>
                                <div class="card mt-2">
                                    <div class="card-body p-0">
                                        <div class="d-block">
                                            <div id="pageContentEditor" class="bg-transparent border-0 h-350px px-3">
                                                {!! $page->content !!}
                                            </div>
                                            <input type="hidden" id="content" name="content"
                                                value="{{ old('content') ?? $page->content }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="footer"
                                        @checked($page->footer == 'yes')>
                                    <label class="form-check-label" for="footer">
                                        {{ __('Show in footer') }}
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-md-flex align-items-center">
                                    <div class="ms-auto mt-3 mt-md-0">
                                        <a href="{{ route('admin.page') }}"><button type="button"
                                                class="btn btn-1 gap-6">
                                                {{ __('Back') }}
                                            </button></a>
                                    </div>
                                    <div class="ms-2 mt-3 mt-md-0">
                                        <button type="submit" class="btn btn-primary gap-6">
                                            {{ __('Save') }}
                                        </button>
                                    </div>
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
