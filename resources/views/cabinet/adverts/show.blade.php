@extends('layouts.sidebar')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Advert') }}</div>
                <div class="card-body">
                    @if ($advert->isDraft())
                        <div class="alert alert-danger">
                            {{ __('It is a draft') }}.
                        </div>
                        @if ($advert->reject_reason)
                            <div class="alert alert-danger">
                                {{ $advert->reject_reason }}
                            </div>
                        @endif
                    @endif

                    @can('manage-own-advert', $advert)
                        <div class="d-flex flex-row mb-3">
                            <a href="{{ route('cabinet.advert.edit', $advert) }}" class="btn btn-primary mr-1">{{ __('Edit') }}</a>
                            <a href="{{ route('cabinet.advert.photos', $advert) }}" class="btn btn-primary mr-1">{{ __('Photos') }}</a>

                            @if ($advert->isDraft())
                                <form method="POST" action="{{ route('cabinet.advert.publish', $advert) }}" class="mr-1">
                                    @csrf
                                    <button class="btn btn-success">{{ __('Publish') }}</button>
                                </form>
                            @endif
                            @if ($advert->isActive())
                                <form method="POST" action="{{ route('cabinet.advert.close', $advert) }}" class="mr-1">
                                    @csrf
                                    <button class="btn btn-success">{{ __('Close') }}</button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('cabinet.advert.destroy', $advert) }}" class="mr-1">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger">{{ __('Delete') }}</button>
                            </form>
                        </div>
                    @endcan

                    <div class="row">
                        <div class="col-md-9">

                            <p class="float-right" style="font-size: 36px;">{{ $advert->price }} &#8381;</p>
                            <h1 style="margin-bottom: 10px">{{ $advert->title  }}</h1>
                            <p>
                                @if ($advert->expires_at)
                                    {{ __('Date') }}: {{ $advert->published_at }} &nbsp;
                                @endif
                                @if ($advert->expires_at)
                                    {{ __('Expires') }}: {{ $advert->expires_at }}
                                @endif
                            </p>

                            <div style="margin-bottom: 20px">
                                <div class="row">
                                    <div class="col-10">
                                        <div style="background: #f6f6f6; border: 1px solid #ddd">
                                            <img src="{{ $advert->firstPhoto() }}" alt="{{ $advert->firstPhoto() }}" class="img-fluid">
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        @foreach($advert->photos as $photo)
                                        <div style="background: #f6f6f6; border: 1px solid #ddd; margin-bottom: 5px;">
                                            <img src="{{ $photo->getUrl() }}" alt="{{ $photo->file }}" class="img-fluid">
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <p>{!! nl2br(e($advert->content)) !!}</p>

                            <table class="table table-bordered">
                                <tbody>
                                    @foreach ($advert->category->getAllAttributes() as $attribute)
                                        <tr>
                                            <th>{{ $attribute->name }}</th>
                                            <td>{{ $advert->getValue($attribute->id) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <p>{{ __('Address') }}: {{ $advert->address }}</p>

                            <div style="margin: 20px 0; border: 1px solid #ddd">
                                <div id="map" style="width: 100%; height: 250px">карта</div>
                            </div>

                            <p style="margin-bottom: 20px">{{ __('Seller') }}: {{ $advert->user->name }}</p>

                            <div class="d-flex flex-row mb-3">
                                {{--<span class="btn btn-success mr-1"><span class="fa fa-envelope"></span> {{ __('Send Message') }}</span>
                                <span class="btn btn-primary phone-button mr-1" data-source="{{ route('adverts.phone', $advert) }}">
                                    <span class="fa fa-phone"></span> <span class="number">{{ __('Show Phone Number') }}</span>
                                </span>--}}
                                {{--@if ($user && $user->hasInFavorites($advert->id))
                                    <form method="POST" action="{{ route('adverts.favorites', $advert) }}" class="mr-1">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-secondary"><span class="fa fa-star"></span> Remove from Favorites</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('adverts.favorites', $advert) }}" class="mr-1">
                                        @csrf
                                        <button class="btn btn-danger"><span class="fa fa-star"></span> Add to Favorites</button>
                                    </form>
                                @endif--}}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div style="height: 400px; background: #f6f6f6; border: 1px solid #ddd; margin-bottom: 20px"></div>
                            <div style="height: 400px; background: #f6f6f6; border: 1px solid #ddd; margin-bottom: 20px"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection