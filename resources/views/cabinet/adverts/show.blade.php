@extends('layouts.sidebar')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Profile') }}</div>
                <div class="card-body">
                    <img class="card-img-top" src="{{ $profile->photo }}" alt="Card image cap">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">{{ $profile->first_name }}</li>
                        <li class="list-group-item">{{ $profile->last_name }}</li>
                        <li class="list-group-item">{{ $profile->location }}</li>
                    </ul>
                    <div class="card-body">
                        <a href="{{ route('cabinet.profile.edit', $profile) }}" class="card-link">{{ __('Edit profile') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection