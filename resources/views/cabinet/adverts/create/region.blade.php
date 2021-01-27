@extends('layouts.sidebar')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Create Advert') }}</div>
                <div class="card-body">
                    @if($region)
                        <p>
                            <a class="btn btn-success" href="{{ route('cabinet.advert.create', [$category, $region]) }}">
                                {{ __('Add Advert for') }} {{ $region->name }}
                            </a>
                        </p>
                    @else
                        <p>
                            <a class="btn btn-success" href="{{ route('cabinet.advert.create', [$category]) }}">
                                {{ __('Add Advert for all regions') }}
                            </a>
                        </p>
                    @endif

                    @if(count($regions))
                        <h3>{{ __('or select nested region') }}</h3>
                        <ul>
                            @foreach($regions as $current)
                                <li>
                                    <a href="{{ route('cabinet.advert.region', [$category, $current]) }}">{{ $current->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection