@extends('layouts.sidebar')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Create Banner') }}</div>
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
                    <form action="{{ route('cabinet.banners.store', [$category, $region]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="title">{{ __('Name') }}</label>
                            <input type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" id="name" name="name" value="{{ old('name') }}" placeholder="Enter name" required>
                            @error('name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="limit">{{ __('Views') }}</label>
                            <input type="number" class="form-control{{ $errors->has('limit') ? ' is-invalid' : '' }}" id="limit" name="limit" value="{{ old('limit') }}" placeholder="Enter views" required>
                            @error('limit')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="url">{{ __('URL') }}</label>
                            <input type="text" class="form-control{{ $errors->has('url') ? ' is-invalid' : '' }}" id="url" name="url" value="{{ old('url') }}" placeholder="Enter url" required>
                            @error('url')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="format">{{ __('Format') }}</label>
                            <select name="format" id="format" class="form-control{{ $errors->has('content') ? ' is-invalid' : '' }}" required>
                                @foreach($formats as $value)
                                    <option value="{{ $value }}"{{ $value === old('format') ? ' selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('format')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="file">{{ __('Banner') }}</label>
                            <input type="file" class="form-control-file{{ $errors->has('file') ? ' is-invalid' : '' }}" id="file" name="file" value="{{ old('file') }}" required>
                            @error('file')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection