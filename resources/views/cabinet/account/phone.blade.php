@extends('layouts.sidebar')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Phone verify') }}</div>
                <div class="card-body">
                    <form action="{{ route('cabinet.account.verify') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>{{ __('Code') }}</label>
                            <input type="text" class="form-control{{ $errors->has('token') ? ' is-invalid' : '' }}" name="token" value="{{ @old('token') }}" placeholder="{{ __('Enter code') }}">
                            @error('token')
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