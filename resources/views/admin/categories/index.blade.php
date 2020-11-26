@extends('layouts.admin')

@section('pageTitle', __('Categories'))

@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('List categories') }}</h3>

                <div class="card-tools">
                    <a href="{{ route('admin.category.create') }}" class="btn btn-primary">{{ __('Create new category') }}</a>
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
                        @foreach($categories as $category)
                            <tr>
                                <th>{{ $category->id }}</th>
                                <td>
                                    @for($i = 0; $i < $category->depth; $i++) &mdash; @endfor
                                    {{ $category->name }}
                                </td>
                                <td>{{ $category->slug }}</td>
                                <td>{{ $category->created_at->format('j F, Y') }}</td>
                                <td>
                                    <form action="{{ route('admin.category.first', $category) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-outline-info" type="submit"><i class="fas fa-angle-double-up"></i></button>
                                    </form>
                                    <form action="{{ route('admin.category.up', $category) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-outline-info" type="submit"><i class="fas fa-angle-up"></i></button>
                                    </form>
                                    <form action="{{ route('admin.category.down', $category) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-outline-info" type="submit"><i class="fas fa-angle-down"></i></button>
                                    </form>
                                    <form action="{{ route('admin.category.last', $category) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-outline-info" type="submit"><i class="fas fa-angle-double-down"></i></button>
                                    </form>
                                    <a href="{{ route('admin.category.show', $category) }}" class="btn btn-outline-info"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.category.edit', $category) }}" class="btn btn-outline-info"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('admin.category.destroy', $category) }}" onclick="event.preventDefault(); document.getElementById('delete-category-form{{ $category->id }}').submit();" class="btn btn-outline-info"><i class="fas fa-trash"></i></a>
                                    <form id="delete-category-form{{ $category->id }}" action="{{ route('admin.category.destroy', $category) }}" method="POST">
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