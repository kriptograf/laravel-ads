@extends('layouts.admin')

@section('pageTitle', __('Edit Region'))

@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Edit Region') . ' ' . $region->name }}</h3>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('admin.region.update', $region) }}">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="parent_id" value="{{ $parent ? $parent->id : null }}">

                    <div class="form-group">
                        <label>{{ __('Name') }}</label>
                        <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="Enter name" name="name" value="{{ old('name', $region->name) }}">
                        @error('name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>{{ __('Slug') }}</label>
                        <input type="text" class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}" placeholder="Enter a name or leave the field blank for automatic generation" name="slug" value="{{ old('slug', $region->slug) }}">
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