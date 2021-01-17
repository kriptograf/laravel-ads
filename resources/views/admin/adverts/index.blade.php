@extends('layouts.admin')

@section('pageTitle', __('Adverts'))

@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('List adverts') }}</h3>
            </div>

            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('ID') }}</th>
                            <th scope="col">{{ __('Title') }}</th>
                            <th scope="col">{{ __('Price') }}</th>
                            <th scope="col">{{ __('Created') }}</th>
                            <th scope="col">{{ __('Status') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <form action="?" method="GET">
                            <tr>
                                <td scope="col">
                                    <input class="col-sm-6" type="text" name="id" value="{{ request('id') }}">
                                </td>
                                <td scope="col">
                                    <input class="col-sm-12" type="text" name="title" value="{{ request('title') }}">
                                </td>
                                <td scope="col">
                                </td>
                                <td scope="col">
                                    <input class="col-sm-12" type="text" name="created_at" value="{{ request('created_at') }}">
                                </td>
                                <td scope="col">
                                    <select class="form-control" name="status">
                                        <option value=""></option>
                                        @foreach($statusList as $key => $status)
                                            <option value="{{ $key }}" @if(request('status') === $key) selected @endif>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td scope="col">
                                    <input type="submit" class="btn btn-info" value="{{ __('Search') }}">
                                </td>
                            </tr>
                        </form>
                        @foreach($adverts as $advert)
                            <tr>
                                <td>{{ $advert->id }}</td>
                                <td>
                                    {{ $advert->title }}
                                </td>
                                <td>{{ $advert->price }}</td>
                                <td>{{ $advert->created_at->format('j F, Y') }}</td>
                                <td>{{ $advert->status }}</td>
                                <td>
                                    <a href="{{ route('admin.advert.show', $advert) }}" class="btn btn-outline-info"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.advert.edit', $advert) }}" class="btn btn-outline-info"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('admin.advert.destroy', $advert) }}" onclick="event.preventDefault(); document.getElementById('delete-category-form{{ $advert->id }}').submit();" class="btn btn-outline-info"><i class="fas fa-trash"></i></a>
                                    <form id="delete-category-form{{ $advert->id }}" action="{{ route('admin.advert.destroy', $advert) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>


            </div>
            <div class="card-footer">

            </div>
        </div>
    </div>
@endsection