@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                {{ __('Home') }}
                <a href="" class="btn btn-info float-right">{{ __('Create ads') }}</a>
            </div>

            <div class="card-body">

                <div class="card card-default mb-3">
                    <div class="card-header">
                        All Categories
                    </div>
                    <div class="card-body pb-0" style="color: #aaa">
                        <div class="row">
                            @foreach (array_chunk($categories, 3) as $chunk)
                                <div class="col-md-3">
                                    <ul class="list-unstyled">
                                        @foreach ($chunk as $current)
                                            <li><a href="{{ route('adverts.index', adverts_path(null, $current)) }}">{{ $current->name }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card card-default mb-3">
                    <div class="card-header">
                        All Regions
                    </div>
                    <div class="card-body pb-0" style="color: #aaa">
                        <div class="row">
                            @foreach (array_chunk($regions, 3) as $chunk)
                                <div class="col-md-3">
                                    <ul class="list-unstyled">
                                        @foreach ($chunk as $current)
                                            <li><a href="{{ route('adverts.index', adverts_path($current, null)) }}">{{ $current->name }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
