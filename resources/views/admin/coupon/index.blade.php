@extends('layouts.admin')
@section('title', $pageTitle)

@section('content')
    @include('include.admin.toast')

    <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col blur-section">
                        @include('include.admin.breadcrumbs')
                    </div>
                    <div class="col-auto ms-auto me-3 blur-section">
                        <div class="btn-list">
                            <span class="d-sm-inline">
                                <a href="javascript:void(0)" class="btn btn-primary btn-5 disabled">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-0" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 5l0 14" />
                                        <path d="M5 12l14 0" />
                                    </svg>
                                    <span class="d-none d-sm-inline-block">{{ __('Create New') }}</span>
                                </a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-body position-relative">
            @include('include.admin.premium-overlay', [
                'message' => __('Coupon management and promotional campaigns are available in the paid version.'),
            ])
            <div class="container-xl blur-section">
                <div class="accordion mb-3" id="couponSearch">
                    <div class="accordion-item">
                        <h4 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#couponSearchForm" aria-expanded="true">
                                {{ __('Search') }}
                            </button>
                        </h4>
                        <div id="couponSearchForm"
                            class="accordion-collapse collapse @if ($isFiltered) show @endif"
                            data-bs-parent="#couponSearch">
                            <div class="accordion-body pt-0">
                                @include('admin.coupon.search')
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="table-responsive border rounded">
                        <table class="table align-middle text-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('SR No') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Code') }}</th>
                                    <th>{{ __('Quantity') }}</th>
                                    <th>{{ __('Redeems') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($coupons as $coupon)
                                    <tr>
                                        <td>{{ $coupons->firstItem() + $loop->index }}</td>
                                        <td>{{ $coupon->name }}</td>
                                        <td>{{ $coupon->code }}</td>
                                        <td>{{ $coupon->quantity }}</td>
                                        <td>{{ $coupon->redeems }}</td>
                                        <td>{{ number_format($coupon->percentage, 2, __('.'), __(',')) }}%<span
                                                class="text-muted">{{ $coupon->type ? __('Redeemable') : __('Discount') }}</span>
                                        </td>
                                        {{-- <td>{{ $coupon->percentage }} --}}
                                        </td>
                                        <td>
                                            <div class="form-switch">
                                                <input type="checkbox" class="form-check-input toggle-coupon-status"
                                                    data-id="{{ $coupon->id }}"
                                                    {{ $coupon->status == 1 ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            <a class="btn" href = "{{ route('admin.coupon.edit', $coupon->id) }}">
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
                    @if ($coupons->hasPages())
                        <div class="mt-2 ms-2 mb-2">
                            {{ $coupons->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
