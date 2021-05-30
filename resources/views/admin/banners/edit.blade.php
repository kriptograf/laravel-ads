@extends('layouts.admin')

@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">{{ __('Edit Banner') }}</div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card-body">
                <form method="POST" action="{{ route('cabinet.banners.edit', $banner) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="name" class="col-form-label">Name</label>
                        <input id="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name', $banner->name) }}" required>
                        @if ($errors->has('name'))
                            <span class="invalid-feedback"><strong>{{ $errors->first('name') }}</strong></span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="limit" class="col-form-label">Views</label>
                        <input id="limit" type="number" class="form-control{{ $errors->has('limit') ? ' is-invalid' : '' }}" name="limit" value="{{ old('limit', $banner->limit) }}" required>
                        @if ($errors->has('limit'))
                            <span class="invalid-feedback"><strong>{{ $errors->first('limit') }}</strong></span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="url" class="col-form-label">URL</label>
                        <input id="url" type="url" class="form-control{{ $errors->has('url') ? ' is-invalid' : '' }}" name="url" value="{{ old('url', $banner->url) }}" required>
                        @if ($errors->has('url'))
                            <span class="invalid-feedback"><strong>{{ $errors->first('url') }}</strong></span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="format">{{ __('Format') }}</label>
                        <select name="format" id="format" class="form-control{{ $errors->has('content') ? ' is-invalid' : '' }}" required>
                            @foreach($formats as $value)
                                <option value="{{ $value }}"{{ $value === old('format', $banner->format) ? ' selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                        @error('format')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection