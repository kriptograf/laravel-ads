@extends('layouts.sidebar')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Create Advert') }}</div>
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
                    <form action="{{ route('cabinet.advert.store', [$category, $region]) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="title">{{ __('Title') }}</label>
                            <input type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" id="title" name="title" value="{{ old('title') }}" placeholder="Enter title" required>
                            @error('title')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="price">{{ __('Price') }}</label>
                            <input type="text" class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}" id="price" name="price" value="{{ old('price') }}" placeholder="Enter price" required>
                            @error('price')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="address">{{ __('Address') }}</label>
                            <input type="text" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" id="address" name="address" value="{{ old('address', $region->getAddress()) }}" placeholder="Enter address" required>
                            @error('address')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="content">{{ __('Content') }}</label>
                            <textarea class="form-control{{ $errors->has('content') ? ' is-invalid' : '' }}" id="content" name="content" placeholder="Enter content" required>
                            {{ old('content') }}
                            </textarea>
                            @error('content')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($category->getAllAttributes())
                            <hr>
                            <h4>{{ __('Characteristics') }}</h4>
                            @foreach($category->getAllAttributes() as $attribute)
                            <div class="form-group">
                                <label for="attribute_{{ $attribute->id }}">{{ $attribute->name }}</label>
                                @if($attribute->isSelect())
                                    <select name="attributes[{{ $attribute->id }}]" id="attribute_{{ $attribute->id }}" class="form-control{{ $errors->has('attributes.' . $attribute->id) ? ' is-invalid' : '' }}">
                                        <option value=""></option>
                                        @foreach($attribute->variants as $variant)
                                            <option value="{{ $variant }}"{{ $variant === old('attributes.' . $attribute->id) ? ' selected' : '' }}>{{ $variant }}</option>
                                        @endforeach
                                    </select>
                                @elseif($attribute->isInteger())
                                    <input type="number" class="form-control{{ $errors->has('attributes.' . $attribute->id) ? ' is-invalid' : '' }}" id="attribute_{{ $attribute->id }}" name="attributes[{{ $attribute->id }}]" value="{{ old('attributes.' . $attribute->id) }}" step="1">
                                @elseif($attribute->isFloat())
                                    <input type="number" class="form-control{{ $errors->has('attributes.' . $attribute->id) ? ' is-invalid' : '' }}" id="attribute_{{ $attribute->id }}" name="attributes[{{ $attribute->id }}]" value="{{ old('attributes.' . $attribute->id) }}" step="0.1">
                                @else
                                    <input type="text" class="form-control{{ $errors->has('attributes.' . $attribute->id) ? ' is-invalid' : '' }}" id="attribute_{{ $attribute->id }}" name="attributes[{{ $attribute->id }}]" value="{{ old('attributes.' . $attribute->id) }}">
                                @endif
                                @error('attributes.' . $attribute->id)
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            @endforeach
                        @endif
                        <hr>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection