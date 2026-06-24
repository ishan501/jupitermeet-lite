@extends('user.profile.index')

@section('profile-content')
    <div class="col-12 col-md-9 d-flex flex-column card-body position-relative">
        <h2 class="mb-4">{{ __('Contacts') }}</h2>
        @include('include.user.message')

        @include('include.admin.premium-overlay', [
            'message' => __('Contact management and import features are available in the paid version.'),
        ])

        <div class="blur-section">
            <div class="row mb-3 ">
                <div class="col-sm-12">
                    <button class="btn btn-primary disabled" title="{{ __('Create Contact') }}">{{ __('Create') }}</button>
                    <button class="btn btn-success disabled" style="margin-left:5px;"
                        title="{{ __('Import Contact') }}">{{ __('Import') }}</button>
                </div>
            </div>
            @if (count($contacts))
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('SR No') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Created') }}</th>
                            <th>{{ __('Updated') }}</th>
                            <th>{{ __('Edit') }}</th>
                            <th>{{ __('Delete') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contacts as $contact)
                            <tr>
                                <td>{{ $contacts->firstItem() + $loop->index }}
                                </td>
                                <td>{{ $contact->name }}</td>
                                <td>{{ $contact->email }}</td>
                                <td>{{ $contact->created_at->diffForHumans() }}
                                </td>
                                <td>{{ $contact->updated_at->diffForHumans() }}
                                </td>
                                <td>
                                    <button class="btn btn-primary disabled" title="{{ __('Edit') }}">
                                        {{ __('Edit') }}
                                    </button>
                                </td>
                                <td>
                                    <button class="btn btn btn-danger disabled">
                                        {{ __('Delete') }}
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if ($contacts->hasPages())
                    <div class="card-footer">
                        <div class="mt-2 ms-2 mb-2">
                            {{ $contacts->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif
            @else
                <p>{{ __('Your contacts will appear here') }}</p>
            @endif
        </div>
    </div>
@endsection
