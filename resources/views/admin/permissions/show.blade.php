@extends('layouts.admin')

@section('pageTitle', __('Permission'))

@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Permission') . ' ' . $permission->name }}</h3>
            </div>

            <div class="card-body">
                {{ $permission->name }}
            </div>
        </div>
    </div>
@endsection