<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="{{ route('admin.dashboard') }}" class="brand-link">
            <!--begin::Brand Image-->
            <img src="{{ asset('assets/img/logo.png') }}" alt="Admin Logo" class="brand-image opacity-75 shadow">
            <!--end::Brand Image-->

            <!--begin::Brand Text-->
            <span class="brand-text fw-light">Admin CNFVAPE</span>
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
                    <a href="{{ url('admin/dashboard') }}" class="nav-link">
                        <i class="nav-icon bi bi-circle"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('admin/category') }}" class="nav-link">
                        <i class="nav-icon bi bi-clipboard"></i>
                        <p>Categories</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('addons.index') }}" class="nav-link">
                        <i class="nav-icon bi bi-clipboard"></i>
                        <p>Add-ons</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('admin/product') }}" class="nav-link">
                        <i class="nav-icon bi bi-clipboard"></i>
                        <p>Products</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('admin/vouchers') }}" class="nav-link">
                        <i class="nav-icon bi bi-clipboard"></i>
                        <p>Vouchers</p>
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
                            <a href="{{url('admin/all-order')}}" class="nav-link"> <i class="nav-icon bi bi-circle"></i>
                                <p>All Orders</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{url('admin/pending-order')}}" class="nav-link"> <i class="nav-icon bi bi-circle"></i>
                                <p>Pending Orders</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{url('admin/completed-order')}}" class="nav-link"> <i class="nav-icon bi bi-circle"></i>
                                <p>Completed Orders</p>
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-clipboard-fill"></i>
                        <p>
                            List
                            <span class="nav-badge badge text-bg-secondary me-3">6</span> <i
                                class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{url('admin/staff-list')}}" class="nav-link"> <i class="nav-icon bi bi-circle"></i>
                                <p>Staff</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{url('admin/user-list')}}" class="nav-link"> <i class="nav-icon bi bi-circle"></i>
                                <p>User</p>
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
