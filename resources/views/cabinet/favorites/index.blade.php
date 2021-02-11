@extends('layouts.sidebar')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ __('Favorites') }}
                </div>
                <div class="card-body">
                    @foreach ($adverts as $advert)
                        <div class="card-body advert">
                            <div class="row">
                                <div class="col-md-3">
                                    <div style="background: #f6f6f6; border: 1px solid #ddd">
                                        <img src="https://via.placeholder.com/180x180.png" alt="">
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <span class="float-right">{{ $advert->price }}</span>
                                    <div class="h4" style="margin-top: 0"><a href="{{ route('cabinet.advert.show', $advert) }}">{{ $advert->title }}</a></div>
                                    <p>Region: <a href="">{{ $advert->region ? $advert->region->name : 'All' }}</a></p>
                                    <p>Category: <a href="">{{ $advert->category->name }}</a></p>
                                    <p>Date: {{ $advert->created_at }}</p>
                                    
                                    <div>
                                        <form action="{{ route('cabinet.favorites.remove', $advert) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button class="fa fa-trash" type="submit">Remove from favorite</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection