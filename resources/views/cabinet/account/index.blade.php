@extends('layouts.sidebar')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Account') }}</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>{{ __('Name') }}:</strong> {{ $user->name }}</li>
                        <li class="list-group-item"><strong>{{ __('Email') }}:</strong> {{ $user->email }}</li>
                        <li class="list-group-item">
                            <strong>{{ __('Phone') }}:</strong> {{ $user->phone }}
                            @if(!$user->isPhoneVerified())
                                <span class="text-danger">{{ __('Phone is not verified!') }}</span>
                                <form action="{{ route('cabinet.account.phone') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-xs btn-success">{{ __('Verify') }}</button>
                                </form>
                            @else
                                <span class="text-success">{{ __('Phone is verified!') }}</span>
                            @endif
                        </li>
                    </ul>
                    <div class="card-body">
                        <a href="{{ route('cabinet.account.edit', $user) }}" class="card-link">{{ __('Edit account') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection