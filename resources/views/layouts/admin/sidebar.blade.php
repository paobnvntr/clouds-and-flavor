<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ route('admin.dashboard') }}" class="brand-link">
            <span class="text-light fw-light">Clouds N Flavor</span>
        </a>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ url('admin/dashboard') }}" class="nav-link">
                        <i class="nav-icon bi bi-bar-chart-line"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('admin/category') }}" class="nav-link">
                        <i class="nav-icon bi bi-tags"></i>
                        <p>Categories</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('addons.index') }}" class="nav-link">
                        <i class="nav-icon bi bi-plus-circle-dotted"></i>
                        <p>Add-ons</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('admin/product') }}" class="nav-link">
                        <i class="nav-icon bi bi-box"></i>
                        <p>Products</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('admin/vouchers') }}" class="nav-link">
                        <i class="nav-icon bi bi-receipt"></i>
                        <p>Vouchers</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-cart-fill"></i>
                        <p>
                            Orders
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview ms-4">
                        <li class="nav-item">
                            <a href="{{url('admin/all-order')}}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>All Orders</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{url('admin/pending-order')}}" class="nav-link">
                                <i class="nav-icon bi bi-hourglass-split"></i>
                                <p>Pending Orders</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{url('admin/completed-order')}}" class="nav-link">
                                <i class="nav-icon bi bi-check-circle"></i>
                                <p>Completed Orders</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-person-lines-fill"></i>
                        <p>
                            Users
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview ms-4">
                        <li class="nav-item">
                            <a href="{{url('admin/staff-list')}}" class="nav-link">
                                <i class="nav-icon bi bi-people-fill"></i>
                                <p>Staff</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{url('admin/user-list')}}" class="nav-link">
                                <i class="nav-icon bi bi-person"></i>
                                <p>User (Customer)</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>