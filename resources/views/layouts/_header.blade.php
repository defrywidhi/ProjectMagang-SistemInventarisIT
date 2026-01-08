<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="bi bi-list"></i>
                </a>
            </li>
        </ul>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    {{-- GANTI FOTO JADI DINAMIS --}}
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random"
                        class="user-image rounded-circle shadow" alt="User Image" />

                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <li class="user-header text-bg-primary">
                        {{-- FOTO DI DALAM DROPDOWN JUGA DIGANTI --}}
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random&color=fff"
                            class="rounded-circle shadow" alt="User Image" />
                        <p>
                            {{-- TAMBAH PENGAMAN ROLE --}}
                            {{ Auth::user()->name }} - {{ Auth::user()->getRoleNames()->first() ?? 'User' }}
                            <small>Dibuat sejak {{ Auth::user()->created_at->format('F d, Y') }}</small>
                        </p>
                    </li>
                    <li class="user-footer">
                        <a href="{{ route('profile.edit') }}" class="btn btn-default btn-flat">Profile</a>
                        <form method="post" action="{{ route('logout') }}" class="d-inline float-end">
                            @csrf
                            <button type="submit" class="btn btn-default btn-flat">Sign out</button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>