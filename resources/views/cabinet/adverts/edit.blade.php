@extends('layouts.sidebar')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Profile') }}</div>
                <div class="card-body">
                    <form action="{{ route('cabinet.profile.update', $profile) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>{{ __('First name') }}</label>
                            <input type="text" class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" name="first_name" value="{{ @old('first_name', $profile->first_name) }}" placeholder="{{ __('Enter first name') }}">
                            @error('first_name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>{{ __('Last name') }}</label>
                            <input type="text" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name" value="{{ @old('last_name', $profile->last_name) }}" placeholder="{{ __('Enter Last name') }}">
                            @error('last_name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>{{ __('Location') }}</label>
                            <input type="text" class="form-control{{ $errors->has('location') ? ' is-invalid' : '' }}" name="location" value="{{ @old('location', $profile->location) }}" placeholder="{{ __('Enter Location name') }}">
                            @error('location')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection