@extends('layouts.sidebar')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Adverts') }}</div>
                <div class="card-body">
                    <div class="region-selector" data-selected="{{ json_encode((array)old('regions')) }}" data-source="{{ route('ajax.regions') }}"></div>
                </div>
            </div>
        </div>
    </div>
@endsection