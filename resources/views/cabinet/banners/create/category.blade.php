@extends('layouts.sidebar')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Create Banner') }}</div>
                <div class="card-body">
                    <h3>{{ __('Select category') }}</h3>
                    @include('cabinet.banners.create.partial._categories', ['categories' => $categories])
                </div>
            </div>
        </div>
    </div>
@endsection