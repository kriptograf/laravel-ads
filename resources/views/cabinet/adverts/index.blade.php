@extends('layouts.sidebar')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Adverts') }}</div>
                <div class="card-body">
                    <a href="{{ route('cabinet.advert.category') }}" class="btn btn-primary">{{ __('Create advert') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection