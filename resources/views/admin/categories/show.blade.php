@extends('layouts.admin')

@section('pageTitle', __('Category'))

@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Category') . ' ' . $category->name }}</h3>
            </div>

            <div class="card-body">
                <table class="table">
                    <tr>
                        <th>Name</th>
                        <td>{{ $category->name }}</td>
                    </tr>
                    <tr>
                        <th>Slug</th>
                        <td>{{ $category->slug }}</td>
                    </tr>
                    <tr>
                        <th>Parent</th>
                        <td>{{ ($category->parent) ? $category->parent->name : '' }}</td>
                    </tr>
                    <tr>
                        <th>Created</th>
                        <td>{{ $category->created_at }}</td>
                    </tr>
                </table>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Attributes') }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.attribute.create', $category) }}" class="btn btn-primary">{{ __('Create attribute for category') . ' ' . $category->name }}</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th>{{ __('Sort') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Required') }}</th>
                                <th></th>
                            </tr>
                            @foreach($attributes as $attribute)
                            <tr>
                                <td>{{ $attribute->sort }}</td>
                                <td>{{ $attribute->name }}</td>
                                <td>{{ $attribute->type }}</td>
                                <td>{{ $attribute->required }}</td>
                                <td>
                                    <a href="{{ route('admin.attribute.show', [$category, $attribute]) }}" class="btn btn-outline-info"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.attribute.edit', [$category, $attribute]) }}" class="btn btn-outline-info"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('admin.attribute.destroy', [$category, $attribute]) }}" onclick="event.preventDefault(); document.getElementById('delete-attribute-form{{ $attribute->id }}').submit();" class="btn btn-outline-info"><i class="fas fa-trash"></i></a>
                                    <form id="delete-attribute-form{{ $attribute->id }}" action="{{ route('admin.category.destroy', [$category, $attribute]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection