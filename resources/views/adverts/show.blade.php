@extends('layouts.app')

@section('search')
    @include('layouts.partials.search', ['category' => $advert->category, 'action' => '?'])
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ __('Advert') }}
                </div>
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

                    @can ('moderateAdvert', $advert)
                        <div class="d-flex flex-row mb-3">
                            <a href="{{ route('admin.advert.edit', $advert) }}" class="btn btn-primary mr-1">{{ __('Edit') }}</a>
                            <a href="{{ route('admin.advert.photos', $advert) }}" class="btn btn-primary mr-1">{{ __('Photos') }}</a>

                            @if ($advert->isDraft())
                                <form method="POST" action="{{ route('admin.advert.publish', $advert) }}" class="mr-1">
                                    @csrf
                                    <button class="btn btn-success">{{ __('Publish') }}</button>
                                </form>
                            @endif
                            <a href="{{ route('admin.advert.reject', $advert) }}" class="btn btn-danger mr-1">{{ __('Reject') }}</a>
                            @if ($advert->isActive())
                                <form method="POST" action="{{ route('admin.advert.close', $advert) }}" class="mr-1">
                                    @csrf
                                    <button class="btn btn-success">{{ __('Close') }}</button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('admin.advert.destroy', $advert) }}" class="mr-1">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger">{{ __('Delete') }}</button>
                            </form>
                        </div>
                    @elsecan('manage-own-advert', $advert)
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
                                        <div style="height: 400px; background: #f6f6f6; border: 1px solid #ddd"></div>
                                    </div>
                                    <div class="col-2">
                                        <div style="height: 100px; background: #f6f6f6; border: 1px solid #ddd"></div>
                                        <div style="height: 100px; background: #f6f6f6; border: 1px solid #ddd"></div>
                                        <div style="height: 100px; background: #f6f6f6; border: 1px solid #ddd"></div>
                                        <div style="height: 100px; background: #f6f6f6; border: 1px solid #ddd"></div>
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
                                <span class="btn btn-success mr-1"><span class="fa fa-envelope"></span> {{ __('Send Message') }}</span>
                                <span class="btn btn-primary phone-button mr-1" data-source="{{ route('adverts.phone', $advert) }}">
                                    <span class="fa fa-phone"></span> <span class="number">{{ __('Show Phone Number') }}</span>
                                </span>
                                @if ($user && $user->hasInFavorites($advert->id))
                                    <form method="POST" action="{{ route('favorites.remove', $advert) }}" class="mr-1">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-secondary"><span class="fa fa-star"></span> Remove from Favorites</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('favorites.add', $advert) }}" class="mr-1">
                                        @csrf
                                        <button class="btn btn-danger"><span class="fa fa-star"></span> Add to Favorites</button>
                                    </form>
                                @endif
                            </div>

                            <hr/>

                            <div class="h3">{{ __('Similar adverts') }}</div>

                            <div class="row">
                                @foreach($similarAdverts as $similarAdvert)
                                    <div class="col-sm-6 col-md-4 pr-0">
                                        <div class="card">
                                            <img class="card-img-top" src="https://images.pexels.com/photos/297933/pexels-photo-297933.jpeg?w=1260&h=750&auto=compress&cs=tinysrgb" alt=""/>
                                            <div class="card-body">
                                                <div class="card-title h4 mt-0" style="margin: 10px 0"><a href="#">The First Thing</a></div>
                                                <p class="card-text" style="color: #666">Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="col-sm-6 col-md-4 pr-0">
                                    <div class="card">
                                        <img class="card-img-top" src="https://images.pexels.com/photos/297933/pexels-photo-297933.jpeg?w=1260&h=750&auto=compress&cs=tinysrgb" alt=""/>
                                        <div class="card-body">
                                            <div class="card-title h4 mt-0" style="margin: 10px 0"><a href="#">The First Thing</a></div>
                                            <p class="card-text" style="color: #666">Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="card">
                                        <img class="card-img-top" src="https://images.pexels.com/photos/297933/pexels-photo-297933.jpeg?w=1260&h=750&auto=compress&cs=tinysrgb" alt=""/>
                                        <div class="card-body">
                                            <div class="card-title h4 mt-0" style="margin: 10px 0"><a href="#">The First Thing</a></div>
                                            <p class="card-text" style="color: #666">Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="card">
                                        <img class="card-img-top" src="https://images.pexels.com/photos/297933/pexels-photo-297933.jpeg?w=1260&h=750&auto=compress&cs=tinysrgb" alt=""/>
                                        <div class="card-body">
                                            <div class="card-title h4 mt-0" style="margin: 10px 0"><a href="#">The First Thing</a></div>
                                            <p class="card-text" style="color: #666">Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
                                        </div>
                                    </div>
                                </div>
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