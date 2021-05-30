@extends('layouts.sidebar')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Create Banner') }}</div>
                <div class="card-body">
                    @if($region)
                        <p>
                            <a class="btn btn-success" href="{{ route('cabinet.banners.banner', [$category, $region]) }}">
                                {{ __('Add Banner for') }} {{ $region->name }}
                            </a>
                        </p>
                    @else
                        <p>
                            <a class="btn btn-success" href="{{ route('cabinet.banners.banner', [$category]) }}">
                                {{ __('Add Banner for all regions') }}
                            </a>
                        </p>
                    @endif

                    @if(count($regions))
                        <h3>{{ __('or select nested region') }}</h3>
                        <ul>
                            @foreach($regions as $current)
                                <li>
                                    <a href="{{ route('cabinet.banners.region', [$category, $current]) }}">{{ $current->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection