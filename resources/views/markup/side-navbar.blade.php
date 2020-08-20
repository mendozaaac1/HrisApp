<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/dashboard" class="brand-link">
        <span class="brand-text font-weight-light float-center"> HRIS-NU</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{Auth::user()->photo == null || Auth::user()->photo != 'profile.png' ? asset('img/profile/'.Auth::user()->photo) : asset('img/profile.png') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="/profile" class="d-block">
                    {{ Auth::user()->name }}
                    <p>{{ Auth::user()->type }}</p>
                </a>
                
            </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-header"><small>MAIN</small></li>
                <li class="nav-item">
                    <router-link to="/dashboard" class="nav-link" {{-- active-class="active" exact --}}>
                        <i class="nav-icon fas fa-tachometer-alt blue"></i>
                        <p>
                            Dashboard
                        </p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/users" class="nav-link">
                        <i class="fas fa-users nav-icon"></i>
                        <p>Users</p>
                    </router-link>
                </li>
                <li class="nav-header"><small>MANAGEMENT</small></li>
                @canany(['isAdmin','isAuthor'])
                 <li class="nav-item">
                    <router-link to="/employees" class="nav-link">
                        <i class="fas fa-user-tie nav-icon pink"></i>
                        <p>Employees</p>
                    </router-link>
                </li>
                 <li class="nav-item">
                    <router-link to="/employeeSchedule" class="nav-link">
                        <i class="fas fa-calendar nav-icon orange"></i>
                        <p>Employee Schedules</p>
                    </router-link>
                </li>
                @endcanany
                <li class="nav-header"><small>ACCOUNT</small></li>
        
           
                <li class="nav-item">
                    <router-link to="/profile" class="nav-link" {{-- active-class="active" exact --}}> {{-- other option for active nav --}}
                        <i class="nav-icon fas fa-user orange"></i>
                        <p>
                            Profile
                        </p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                        <i class="nav-icon fas fa-power-off red"></i>
                        <p>
                            {{ __('Logout') }}
                        </p>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>