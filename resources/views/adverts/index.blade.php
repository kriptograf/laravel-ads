@extends('layouts.app')

@section('search')
    @include('layouts.partials.search', ['category' => $category, 'action' => '?'])
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ __('Adverts') }}
                </div>
                <div class="card-body">
                    @if ($categories)
                        <div class="card card-default mb-3">
                            <div class="card-header">
                                @if ($category)
                                    Categories of {{ $category->name }}
                                @else
                                    Categories
                                @endif
                            </div>
                            <div class="card-body pb-0" style="color: #aaa">
                                <div class="row">
                                    @foreach (array_chunk($categories, 3) as $chunk)
                                        <div class="col-md-3">
                                            <ul class="list-unstyled">
                                                @foreach ($chunk as $currentCategory)
                                                    <li>
                                                        <a href="{{ route('adverts.index', array_merge(['adverts_path' => adverts_path($region, $currentCategory)], request()->all())) }}">{{ $currentCategory->name }}</a>
                                                        ({{ $categoriesCounts[$currentCategory->id] ?? 0 }})
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($regions)
                        <div class="card card-default mb-3">
                            <div class="card-header">
                                @if ($region)
                                    Regions of {{ $region->name }}
                                @else
                                    Regions
                                @endif
                            </div>
                            <div class="card-body pb-0" style="color: #aaa">
                                <div class="row">
                                    @foreach (array_chunk($regions, 3) as $chunk)
                                        <div class="col-md-3">
                                            <ul class="list-unstyled">
                                                @foreach ($chunk as $currentRegion)
                                                    <li>
                                                        <a href="{{ route('adverts.index', array_merge(['adverts_path' => adverts_path($currentRegion, $category)], request()->all())) }}">{{ $currentRegion->name }}</a>
                                                        ({{ $regionsCounts[$currentRegion->id] ?? 0 }})
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                        <div class="row">
                            <div class="col-md-9">

                                <div class="adverts-list">
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
                                                    <div class="h4" style="margin-top: 0"><a href="{{ route('adverts.show', $advert) }}">{{ $advert->title }}</a></div>
                                                    <p>Region: <a href="">{{ $advert->region ? $advert->region->name : 'All' }}</a></p>
                                                    <p>Category: <a href="">{{ $advert->category->name }}</a></p>
                                                    <p>Date: {{ $advert->created_at }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                {{ $adverts->links() }}
                            </div>
                            <div class="col-md-3">
                                <div
                                    class="banner"
                                    data-format="240x400"
                                    data-url="{{ route('banner.get') }}"
                                    data-category="{{ $category ? $category->id : '' }}"
                                    data-region="{{ $region ? $region->id : ''  }}">

                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection