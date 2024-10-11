<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="{{ route('staff.dashboard') }}" class="brand-link">
            <!--begin::Brand Image-->
            <img src="{{ asset('assets/img/logo.png') }}" alt="Staff Logo" class="brand-image opacity-75 shadow">
            <!--end::Brand Image-->

            <!--begin::Brand Text-->
            <span class="brand-text fw-light">Staff CNFVAPE</span>
            <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->

    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                {{-- <li class="nav-item menu-open"> 
                    <a href="#" class="nav-link"> 
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>
                            Sample
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"> <a href="#" class="nav-link"> <i
                                    class="nav-icon bi bi-circle"></i>
                                <p>Sample tree</p>
                            </a>
                        </li>
                    </ul>
                </li> --}}

                <li class="nav-item">
                    <a href="{{ url('staff/dashboard') }}" class="nav-link">
                        <i class="nav-icon bi bi-circle"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('/staff/pos') }}" class="nav-link">
                        <i class="nav-icon bi bi-clipboard"></i>
                        <p>POS</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-clipboard-fill"></i>
                        <p>
                            Orders
                            <span class="nav-badge badge text-bg-secondary me-3">6</span> <i
                                class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{url('/staff/orders')}}" class="nav-link"> <i class="nav-icon bi bi-circle"></i>
                                <p>All Orders</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{url('staff/pending-orders')}}" class="nav-link"> <i class="nav-icon bi bi-circle"></i>
                                <p>Pending Orders</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{url('staff/completed-orders')}}" class="nav-link"> <i class="nav-icon bi bi-circle"></i>
                                <p>Completed Orders</p>
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="nav-header">LABELS</li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <p class="text">Settings</p>
                    </a>
                </li>

            </ul>
            <!--end::Sidebar Menu-->
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->
