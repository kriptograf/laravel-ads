@extends('layouts.admin')

@section('pageTitle', __('Regions'))

@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('List regions') }}</h3>

                <div class="card-tools">
                    <a href="{{ route('admin.region.create') }}" class="btn btn-primary">{{ __('Create new region') }}</a>
                </div>
            </div>

            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('ID') }}</th>
                            <th scope="col">{{ __('Name') }}</th>
                            <th scope="col">{{ __('Slug') }}</th>
                            <th scope="col">{{ __('Created') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($regions as $region)
                            <tr>
                                <th>{{ $region->id }}</th>
                                <td>{{ $region->name }}</td>
                                <td>{{ $region->slug }}</td>
                                <td>{{ $region->created_at->format('j F, Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.region.show', $region) }}"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.region.edit', $region) }}"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('admin.region.destroy', $region) }}" onclick="event.preventDefault(); document.getElementById('delete-region-form{{ $region->id }}').submit();"><i class="fas fa-trash"></i></a>
                                    <form id="delete-region-form{{ $region->id }}" action="{{ route('admin.region.destroy', $region) }}" method="POST">
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
                {{ $regions->links() }}
            </div>
        </div>
    </div>
@endsection