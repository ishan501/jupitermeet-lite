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
                    <!-- Page title actions -->
                    <div class="col-auto ms-auto me-3 blur-section">
                        <div class="btn-list">
                            <span class="d-none d-sm-inline">
                                <a href="javascript:void(0)" class="btn hideLoader disabled">
                                    {{ __('Export') }}
                                </a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-body position-relative">
            @include('include.admin.premium-overlay', [
                'message' => __('Transaction history and payment details are available in the paid version.'),
            ])
            <div class="container-xl blur-section">
                <div class="accordion mb-3" id="transactionSearch">
                    <div class="accordion-item">
                        <h4 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#transactionSearchForm" aria-expanded="true">
                                {{ __('Search') }}
                            </button>
                        </h4>
                        <div id="transactionSearchForm"
                            class="accordion-collapse collapse @if ($isFiltered) show @endif"
                            data-bs-parent="#transactionSearch">
                            <div class="accordion-body pt-0">
                                @include('admin.transaction.search')
                            </div>
                        </div>
                    </div>
                </div>
                @if (getSetting('PAYMENT_MODE') == 'disabled')
                    <div class="text-center mb-3">
                        <a href="{{ route('admin.setting.application') }}"
                            class="badge bg-yellow text-yellow-fg p-2">{{ __('The payment mode is disabled, enable now to make the features paid') }}</a>
                    </div>
                @endif
                <div class="card">
                    <div class="table-responsive border rounded">
                        <table class="table align-middle text-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('SR No') }}</th>
                                    <th>{{ __('Username') }}</th>
                                    <th>{{ __('Plan') }}</th>
                                    <th>{{ __('Coupon') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Currency') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Gateway') }}</th>
                                    <th>{{ __('Transaction ID') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Transaction Date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($payments as $payment)
                                    <tr>
                                        <td>{{ $payments->firstItem() + $loop->index }}</td>
                                        <td>{{ $payment->user ? $payment->user->username : "-" }}</td>
                                        <td>{{ $payment->plan->name }}</td>
                                        <td>{{ $payment->coupon ? $payment->coupon->name : '-' }}</td>
                                        <td>{{ $payment->amount }}</td>
                                        <td>{{ $payment->currency }}</td>
                                        <td>{{ $payment->interval }}</td>
                                        <td>{{ $payment->gateway }}</td>
                                        <td>{{ $payment->payment_id }}</td>
                                        <td>{{ $payment->status }}</td>
                                        <td title="{{ $payment->created_at_custom }}">{{ $payment->created_at }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td>{{ __('No Records Found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($payments->hasPages())
                        <div class="mt-2 ms-2 mb-2">
                            {{ $payments->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
