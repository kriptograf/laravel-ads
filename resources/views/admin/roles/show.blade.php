@extends('layouts.admin')

@section('pageTitle', __('Role'))

@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Role') . ' ' . $role->name }}</h3>
            </div>

            <div class="card-body">
                <table class="table table-hover text-nowrap">
                    <tr>
                        <td><label>{{ __('Name') }}:</label> {{ $role->name }}</td>
                        <td><label>{{ __('Guard') }}:</label> {{ $role->guard_name }}</td>
                        <td><label>{{ __('Created') }}:</label> {{ $role->created_at }}</td>
                    </tr>
                </table>

                <form action="{{ route('admin.role.permission', $role) }}" method="POST">
                    @csrf
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Permissions') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row p-md-2">
                                @foreach($permissions as $permission)
                                    <div class="form-check col-md-4">
                                        <input class="form-check-input" type="checkbox" name="permission[]" value="{{ $permission->name }}" @if(in_array($permission->id, $role->getAssignedPermissionsIds())) checked @endif>
                                        <label class="form-check-label">{{ $permission->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-info">{{ __('Save') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection