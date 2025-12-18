<style>
    /* === NAVBAR PORNHUB STYLE === */
    .navbar-ph {
        background-color: #000 !important;
        padding: 12px 25px;
        border-bottom: 2px solid #ffa31a;
    }

    .navbar-ph .nav-link,
    .navbar-ph .navbar-nav .nav-item i,
    .navbar-ph .nav-item,
    .navbar-ph input {
        color: #fff !important;
    }

    .navbar-ph input {
        background: #1a1a1a;
        border: 1px solid #333;
        border-radius: 6px;
        padding-left: 35px;
        color: #fff;
    }

    .navbar-ph .search-icon {
        position: absolute;
        margin-left: 10px;
        color: #ffa31a !important;
        font-size: 20px;
    }

    .navbar-ph .dropdown-menu {
        background-color: #111;
        border: 1px solid #222;
    }

    .navbar-ph .dropdown-menu .dropdown-item {
        color: #fff;
    }

    .navbar-ph .dropdown-menu .dropdown-item:hover {
        background-color: #ffa31a;
        color: #000 !important;
    }

    .navbar-ph .avatar img {
        border: 2px solid #ffa31a;
    }
</style>


<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center navbar-ph">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm" style="color:#fff;"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

        <!-- Search -->
        <div class="navbar-nav align-items-center position-relative me-3">
            <i class="bx bx-search search-icon"></i>
            <input type="text" class="form-control shadow-none" placeholder="Search...">
        </div>

        <ul class="navbar-nav flex-row align-items-center ms-auto">

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="#" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="../assets/img/avatars/2.jpeg" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="../assets/img/avatars/2.jpeg" alt class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block text-white">Fatahillah Akbar</span>
                                    <small class="text-muted">Admin</small>
                                </div>
                            </div>
                        </a>
                    </li>

                    <li><div class="dropdown-divider"></div></li>

                    <li>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="bx bx-user me-2"></i>
                            <span>My Profile</span>
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bx bx-cog me-2"></i>
                            <span>Settings</span>
                        </a>
                    </li>

                    <li><div class="dropdown-divider"></div></li>

                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>
            <!-- /User -->
        </ul>
    </div>
</nav>
