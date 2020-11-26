@extends('layouts.admin')

@section('pageTitle', __('Create Region'))

@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Create new region') }}</h3>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('admin.region.store') }}">
                    @csrf

                    <input type="hidden" name="parent_id" value="{{ $parent ? $parent->id : null }}">

                    <div class="form-group">
                        <label>{{ __('Name') }}</label>
                        <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="Enter name" name="name" value="{{ old('name') }}">
                        @error('name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>{{ __('Slug') }}</label>
                        <input type="text" class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}" placeholder="Enter a name or leave the field blank for automatic generation" name="slug" value="{{ old('slug') }}">
                        @error('slug')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection