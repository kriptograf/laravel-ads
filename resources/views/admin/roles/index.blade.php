@extends('layouts.admin')

@section('pageTitle', __('Roles'))

@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('List roles') }}</h3>

                <div class="card-tools">
                    <a href="{{ route('admin.role.create') }}" class="btn btn-primary">{{ __('Create new role') }}</a>
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
                        @foreach($roles as $role)
                            <tr>
                                <th>{{ $role->id }}</th>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->guard_name }}</td>
                                <td>{{ $role->updated_at }}</td>
                                <td>{{ $role->created_at }}</td>
                                <td>
                                    <a href="{{ route('admin.role.show', $role) }}"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.role.edit', $role) }}"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('admin.role.destroy', $role) }}" onclick="event.preventDefault(); document.getElementById('delete-role-form{{ $role->id }}').submit();"><i class="fas fa-trash"></i></a>
                                    <form id="delete-role-form{{ $role->id }}" action="{{ route('admin.role.destroy', $role) }}" method="POST">
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
                {{ $roles->links() }}
            </div>
        </div>
    </div>
@endsection