<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ route('staff.dashboard') }}" class="brand-link">
            <span class="text-light fw-light">Clouds N Flavor</span>
        </a>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ url('/staff/order-dashboard') }}" class="nav-link"> <i
                            class="nav-icon bi bi-bar-chart-line"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('/staff/pos') }}" class="nav-link">
                        <i class="nav-icon bi bi-cash-coin"></i>
                        <p>POS</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-cart-fill"></i>
                        <p>
                            Online Orders
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('/staff/orders') }}" class="nav-link"> <i class="nav-icon bi bi-circle"></i>
                                <p>All Orders</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('staff/online-pending') }}" class="nav-link"> <i
                                    class="nav-icon bi bi-hourglass-split"></i>
                                <p>Pending Orders</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('staff/completed-orders') }}" class="nav-link"> <i
                                    class="nav-icon bi bi-check-circle"></i>
                                <p>Completed Orders</p>
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-cart-fill"></i>
                        <p>
                            POS Orders
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('/staff/pos/orders') }}" class="nav-link"> <i
                                    class="nav-icon bi bi-circle"></i>
                                <p>All Orders</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('staff/pos/pending-orders') }}" class="nav-link"> <i
                                    class="nav-icon bi bi-hourglass-split"></i>
                                <p>Pending Orders</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('staff/pos/completed-orders') }}" class="nav-link"> <i
                                    class="nav-icon bi bi-check-circle"></i>
                                <p>Completed Orders</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>