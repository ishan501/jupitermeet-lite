@extends('layouts.admin')

@section('page', $page)
@section('title', $page)

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        {{ __('Admin Dashboard') }}
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="row row-cards">
                        <div class="col-sm-6 col-lg-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="bg-primary text-white avatar">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-users">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                                    <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                                    <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium">
                                                {{ $data['user'] }}
                                            </div>
                                            <div class="text-secondary">
                                                {{ __('Users') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="bg-warning text-white avatar">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-video">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M15 10l4.553 -2.276a1 1 0 0 1 1.447 .894v6.764a1 1 0 0 1 -1.447 .894l-4.553 -2.276v-4z" />
                                                    <path
                                                        d="M3 6m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium">
                                                {{ $data['meeting'] }}
                                            </div>
                                            <div class="text-secondary">
                                                {{ __('Meetings') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="bg-success text-white avatar">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-currency-dollar">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                                                    <path d="M12 3v3m0 12v3" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium">
                                                {{ $data['income'] }}
                                            </div>
                                            <div class="text-secondary">
                                                {{ __('Income') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="bg-red text-white avatar">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-lock-password">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z" />
                                                    <path d="M8 11v-4a4 4 0 1 1 8 0v4" />
                                                    <path d="M15 16h.01" />
                                                    <path d="M12.01 16h.01" />
                                                    <path d="M9.02 16h.01" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium">
                                                {{ ucfirst(getSetting('AUTH_MODE')) }}
                                            </div>
                                            <div class="text-secondary">
                                                {{ __('Auth Mode') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-lg-6 col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0">{{ __('Users') }}</h3>
                        </div>
                        <div class="card-body">
                            <div id="usersPaidFreePieChart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0">{{ __('Income') }} <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                    height="16" fill="currentColor" class="bi bi-gem" viewBox="0 0 16 16">
                                    <path
                                        d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
                                </svg></h3>
                        </div>
                        <div class="card-body">
                            <div id="incomeBarGraph"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-lg-6 col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0">{{ __('User Registrations') }}</h3>
                        </div>
                        <div class="card-body">
                            <div id="userRegistrationBarGraph"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0">{{ __('Meetings') }}</h3>
                        </div>
                        <div class="card-body">
                            <div id="meetingsBarGraph"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('/js/apexcharts.min.js') }}" defer></script>

    <script>
        const freeUsers = {{ $data['freeUsers'] }}
            const paidUsers = {{ $data['paidUsers'] }}
            const monthlyIncome = {!! json_encode(json_decode($data['montlyIncome'], true)) !!};
        const monthlyUserRegistered = {!! json_encode(json_decode($data['userGraph'], true)) !!};
        const monthlyMeetings = {!! json_encode(json_decode($data['meetingGraph'], true)) !!};
    </script>
@endsection