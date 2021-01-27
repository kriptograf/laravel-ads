<div class="card">
    <div class="card-header">{{ Auth::user()->name }}</div>
    <div class="card-body">
        <aside class="main-sidebar">
            <div class="sidebar">
                <div class="text-center">
                    <img class="profile-user-img img-fluid" src="{{ Auth::user()->profile->photo }}" alt="User profile picture">
                </div>

                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ route('cabinet.advert') }}" class="nav-link">
                                {{ __('Adverts') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('cabinet.favorites') }}" class="nav-link">
                                {{ __('Favorites') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('cabinet.profile') }}" class="nav-link">
                                {{ __('Profile') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('cabinet.account') }}" class="nav-link">
                                {{ __('Account') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}" class="nav-link">
                                {{ __('Users') }}
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>
    </div>
</div>

