@extends('layouts.sidebar')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Profile') }}</div>
                <div class="card-body">
                    <div class="alert alert-danger">
                        Вы не можете удалить профиль. Обратитесь в службу поддержки.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection