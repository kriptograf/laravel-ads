@extends('layouts.admin')

@section('pageTitle', __('Users'))

@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Users') }}</h3>

                <div class="card-tools">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">{{ __('Create new user') }}</a>
                </div>
            </div>

            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('ID') }}</th>
                            <th scope="col">{{ __('Name') }}</th>
                            <th scope="col">{{ __('Email') }}</th>
                            <th scope="col">{{ __('Roles') }}</th>
                            <th scope="col">{{ __('Verified') }}</th>
                            <th scope="col">{{ __('Created') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <form action="?" method="GET">
                        <tr>
                            <td scope="col">
                                <input class="col-sm-6" type="text" name="id" value="{{ request('id') }}">
                            </td>
                            <td scope="col">
                                <input class="col-sm-12" type="text" name="name" value="{{ request('name') }}">
                            </td>
                            <td scope="col">
                                <input class="col-sm-12" type="text" name="email" value="{{ request('email') }}">
                            </td>
                            <td scope="col">
                                <select class="form-control" name="role">
                                    <option value=""></option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" @if(request('role') === $role->name) selected @endif>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td scope="col">
                                <input type="submit" class="btn btn-info" value="{{ __('Search') }}">
                            </td>
                            <td scope="col"></td>
                        </tr>
                        </form>
                        @foreach($users as $user)
                            <tr>
                                <th>{{ $user->id }}</th>
                                <td><a href="{{ route('admin.users.show', $user) }}">{{ $user->name }}</a></td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @include('admin.users.partial.roles')
                                </td>
                                <td>{{ ($user->email_verified_at) ? $user->email_verified_at->format('j F, Y') : 'Не подтвержден' }}</td>
                                <td>{{ $user->created_at->format('j F, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>


            </div>
            <div class="card-footer">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection