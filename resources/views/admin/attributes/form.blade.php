@extends('layouts.admin')

@section('pageTitle', __('Create Attribute'))

@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Create Attribute') }}</h3>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('admin.attribute.store', $category) }}">
                    @csrf

                    <div class="form-group">
                        <label for="type-select">{{ __('Type attribute') }}</label>
                        <select class="form-control" id="type-select" name="type">
                            @foreach($types as $key => $type)
                            <option value="{{ $key }}">
                                {{ $type }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Name attribute') }}</label>
                        <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="Enter name" name="name" value="{{ old('name') }}">
                        @error('name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>{{ __('Sort') }}</label>
                        <input type="text" class="form-control{{ $errors->has('sort') ? ' is-invalid' : '' }}" placeholder="Enter a sort" name="sort" value="{{ old('sort') }}">
                        @error('sort')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="variants" class="col-form-label">Variants</label>
                        <textarea id="variants" type="text" class="form-control{{ $errors->has('variants') ? ' is-invalid' : '' }}" name="variants">{{ old('variants') }}</textarea>
                        @error('variants')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="hidden" name="required" value="0">
                            <input type="checkbox" class="form-check-input" id="check-required" name="required" value="1" {{ old('required') ? 'checked' : '' }}>
                            <label class="form-check-label" for="check-required">{{ __('Required') }}</label>
                            @error('required')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection