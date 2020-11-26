@foreach($user->getRoleNames() as $role)
    @if($role === 'admin')
        <span class="badge badge-danger">{{ $role }}</span>
    @elseif($role === 'user')
        <span class="badge badge-secondary">{{ $role }}</span>
    @else
        <span class="badge badge-info">{{ $role }}</span>
    @endif
@endforeach