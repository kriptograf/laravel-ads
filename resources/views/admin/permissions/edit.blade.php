@extends('layouts.admin')

@section('pageTitle', __('Edit Permission'))

@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Edit permission') . ' ' . $permission->name }}</h3>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('admin.permission.update', $permission) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>{{ __('Name') }}</label>
                        <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="Enter name" name="name" value="{{ old('name', $permission->name) }}">
                        @error('name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection