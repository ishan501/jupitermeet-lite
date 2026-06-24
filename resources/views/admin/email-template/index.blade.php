@extends('layouts.admin')
@section('title', $pageTitle)

@section('content')
    @include('include.admin.toast')

    <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        @include('include.admin.breadcrumbs')
                    </div>
                </div>
            </div>
        </div>
        <div class="page-body">
            <div class="container-xl">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th>{{ __('SR No') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Slug') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($emailTemplates as $emailTemplate)
                                    <tr>
                                        <td>{{ $emailTemplates->firstItem() + $loop->index }}</td>
                                        <td>{{ $emailTemplate->name }}</td>
                                        <td>{{ $emailTemplate->slug }}</td>
                                        <td>
                                            <a class="btn"
                                                href="{{ route('admin.email-template.edit', $emailTemplate->id) }}">
                                                {{ __('Edit') }}
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td>{{ __('No Records Found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($emailTemplates->hasPages())
                        <div class="mt-2 ms-2 mb-2">
                            {{ $emailTemplates->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
