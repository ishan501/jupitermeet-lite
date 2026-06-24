@extends('user.profile.index')

@section('profile-content')
    <div class="col-12 col-md-9 d-flex flex-column card-body position-relative">
        <h2 class="mb-4">{{ __('Payments') }}</h2>

        @include('include.admin.premium-overlay', [
            'message' => __('Payment history and invoice downloads are available in the paid version.'),
        ])

        <div class="blur-section">
            @if (count($payments))
                <div class="table-responsive showToastAbove">
                    <table class="table card-table table-vcenter text-nowrap datatable ">
                        <thead>
                            <tr>
                                <th>{{ __('SR No') }}</th>
                                <th>{{ __('Plan') }}</th>
                                <th>{{ __('Coupon') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Currency') }}</th>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Gateway') }}</th>
                                <th>{{ __('Transaction ID') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Transaction Date') }}</th>
                                <th>{{ __('Invoice') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $key => $value)
                                <tr>
                                    <td>{{ $payments->firstItem() + $loop->index }}</td>
                                    <td>{{ $value->plan->name }}</td>
                                    <td>{{ $value->coupon ? $value->coupon->name : '-' }}
                                    </td>
                                    <td>{{ $value->amount }}</td>
                                    <td>{{ $value->currency }}</td>
                                    <td>{{ ucfirst($value->interval) }}</td>
                                    <td>{{ ucfirst($value->gateway) }}</td>
                                    <td>{{ $value->payment_id }}</td>
                                    <td>{{ ucfirst($value->status) }}</td>
                                    <td title="{{ $value->created_at }}">{{ $value->created_at->diffForHumans() }}</td>
                                    <th><a href="{{ route('profile.payment.invoice', base64_encode($value->id)) }}"
                                            target="_blank" class="disabled"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                <path
                                                    d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                            </svg></a>
                                    </th>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="mt-2 ms-2">
                        {{ $payments->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @else
                <p class="showToastAbove">{{ __('Your payment history will appear here.') }}</p>
            @endif
        </div>
    </div>
@endsection
