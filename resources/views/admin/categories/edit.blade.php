@extends('layouts.admin')

@section('pageTitle', __('Edit Category'))

@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Edit Category') . ' ' . $category->name }}</h3>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('admin.category.update', $category) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="categoties-select">{{ __('Parent Category') }}</label>
                        <select class="form-control" id="categoties-select" name="parent_id">
                            <option></option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" @if($category->parent_id === $cat->id) selected @endif>
                                    @for($i = 0; $i < $cat->depth; $i++) &mdash; @endfor
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Name') }}</label>
                        <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="Enter name" name="name" value="{{ old('name', $category->name) }}">
                        @error('name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>{{ __('Slug') }}</label>
                        <input type="text" class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}" placeholder="Enter a name or leave the field blank for automatic generation" name="slug" value="{{ old('slug', $category->slug) }}">
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