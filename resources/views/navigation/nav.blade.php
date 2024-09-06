<style>

</style>
<nav class="layout-navbar container-fluid navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>
    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        {{-- <a class="nav-link dropdown-toggle hide-arrow d-flex align-items-center" href="javascript:void(0);"
            data-bs-toggle="dropdown">
            <div class="avatar">
                <img src="{{ asset('assets/img/illustrations/user-avatar.jpg') }}" alt
                    class="w-px-40 h-auto rounded-circle" />
            </div>
            <span class="user-name ms-2">{{ Auth::user()->fname }} {{ Auth::user()->lname }}</span>
        </a> --}}
        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- Notifications -->
            <li class="nav-item lh-1 me-3">
                <a href="" class="nav-link">
                    <i class='bx bx-bell icon-notification'></i>
                </a>
            </li>
            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow d-flex align-items-center" href="javascript:void(0);"
                    data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ asset('assets/img/illustrations/user-avatar.jpg') }}" alt
                            class="w-px-40 h-auto rounded-circle" />
                    </div>
                    <span class="user-name ms-2">{{ Auth::user()->fname }} {{ Auth::user()->lname }}</span>

                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ asset('assets/img/illustrations/user-avatar.jpg') }}" alt
                                            class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <small class="text-muted">
                                        {{ role() }}
                                    </small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('account-settings', Auth::user()->id) }}">
                            <i class="bx bx-user me-2"></i>
                            <span class="align-middle">My Profile</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                            <i class="bx bx-power-off me-2"></i><span class="align-middle">{{ __('Logout') }}</span>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>
