<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3 sidebar-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link d-flex {{ Request::is('dashboard') ? 'active' : ''}}" aria-current="page" href="{{ route('dashboard.index') }}">
                    <i class="ti ti-home fs-5" style="margin-right: 8px"></i>Home
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex {{ Request::is('dashboard/courses*') ? 'active' : ''}}" href="{{ route('courses.index') }}">
                    <i class="ti ti-book fs-5" style="margin-right: 8px"></i>Courses
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex {{ Request::is('dashboard/users*') ? 'active' : ''}}" href="{{ route('users.index') }}">
                    <i class="ti ti-user fs-5" style="margin-right: 8px"></i>Users
                </a>
            </li>
        </ul>
    </div>
</nav>