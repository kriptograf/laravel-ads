@extends('layouts.admin')

@section('pageTitle', __('Attribute'))

@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Attribute') . ' ' . $attribute->name }}</h3>
            </div>

            <div class="card-body">
                <table class="table">
                    <tr>
                        <th>Name</th>
                        <td>{{ $attribute->name }}</td>
                    </tr>
                    <tr>
                        <th>Type</th>
                        <td>{{ $attribute->type }}</td>
                    </tr>
                    <tr>
                        <th>Required</th>
                        <td>{{ ($attribute->required) ? 'Yes' : 'No' }}</td>
                    </tr>
                    <tr>
                        <th>Variants</th>
                        <td>
                            @foreach($attribute->variants as $variant)
                                <p>{{ $variant }}</p>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>Sort</th>
                        <td>{{ $attribute->sort }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection