@extends('layouts.admin')

@section('pageTitle', __('Permissions'))

@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('List permissions') }}</h3>

                <div class="card-tools">
                    <a href="{{ route('admin.permission.create') }}" class="btn btn-primary">{{ __('Create new permission') }}</a>
                </div>
            </div>

            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('ID') }}</th>
                            <th scope="col">{{ __('Name') }}</th>
                            <th scope="col">{{ __('Guard') }}</th>
                            <th scope="col">{{ __('Updated') }}</th>
                            <th scope="col">{{ __('Created') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permissions as $permission)
                            <tr>
                                <th>{{ $permission->id }}</th>
                                <td>{{ $permission->name }}</td>
                                <td>{{ $permission->guard_name }}</td>
                                <td>{{ $permission->updated_at }}</td>
                                <td>{{ $permission->created_at }}</td>
                                <td>
                                    <a href="{{ route('admin.permission.show', $permission) }}"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.permission.edit', $permission) }}"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('admin.permission.destroy', $permission) }}" onclick="event.preventDefault(); document.getElementById('delete-role-form{{ $permission->id }}').submit();"><i class="fas fa-trash"></i></a>
                                    <form id="delete-role-form{{ $permission->id }}" action="{{ route('admin.permission.destroy', $permission) }}" method="POST">
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
                {{ $permissions->links() }}
            </div>
        </div>
    </div>
@endsection