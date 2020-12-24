@extends('layouts.sidebar')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Advert') }}</div>
                <div class="card-body">
                    тут будет просмотр объявления
                    <pre>
                        {{ print_r($advert) }}
                    </pre>
                </div>
            </div>
        </div>
    </div>
@endsection