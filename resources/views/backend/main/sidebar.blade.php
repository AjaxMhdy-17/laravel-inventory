<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="{{ asset('backend/assets/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Laravel Inventory</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <a href={{ route('admin.profile.index') }}>
                <div class="image">
                    <img src="{{ asset('backend/assets/dist/img/avatar5.png') }}" class="img-circle elevation-2"
                        alt="User Image">
                </div>
                <div class="info">
                    <a href={{ route('admin.profile.index') }} class="d-block">{{ $user->name }}</a>
                </div>
            </a>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <li class="nav-item">
                    <a href="{{ route('admin.dashboard.index') }}"
                        class="nav-link {{ request()->routeIs('admin.dashboard.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.supplier.index') }}"
                        class="nav-link {{ request()->routeIs('admin.supplier.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Supplier
                        </p>
                    </a>
                </li>
                <li
                    class="nav-item {{ request()->routeIs('admin.customer.*') ? ' menu-is-opening menu-open active' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-address-book" aria-hidden="true"></i>
                        <p>
                            Customer
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.customer.all.index') }}"
                                class="nav-link {{ request()->routeIs('admin.customer.all*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Customer List</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.customer.credit.index') }}"
                                class="nav-link {{ request()->routeIs('admin.customer.credit*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Credit Customer List</p>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a href="{{ route('admin.customer.paid.index') }}"
                                class="nav-link {{ request()->routeIs('admin.customer.paid*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Paid Customer List</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li
                    class="nav-item {{ request()->routeIs('admin.product.*') ? ' menu-is-opening menu-open active' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-square" aria-hidden="true"></i>
                        <p>
                            Product
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.product.all.index') }}"
                                class="nav-link {{ request()->routeIs('admin.product.all*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Product</p>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a href="{{ route('admin.product.category.index') }}"
                                class="nav-link {{ request()->routeIs('admin.product.category*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Category</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.product.unit.index') }}"
                                class="nav-link {{ request()->routeIs('admin.product.unit*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Unit</p>
                            </a>
                        </li>

                        {{-- <li class="nav-item">
                            <a href="{{ route('admin.product.purchase.index') }}"
                                class="nav-link {{ request()->routeIs('admin.product.purchase*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Purchase
                                </p>
                            </a>
                        </li> --}}
                    </ul>
                </li>
                <li
                    class="nav-item {{ request()->routeIs('admin.invoice.*') ? ' menu-is-opening menu-open active' : '' }}">
                    <a href="#" class="nav-link">
                        {{-- <i class=" fa fa-address-book" aria-hidden="true"></i> --}}
                        {{-- <i class="fa fa-ioxhost  nav-icon" aria-hidden="true"></i> --}}
                        {{-- <i class="nav-icon fas fa-snowflake-o" aria-hidden="true"></i> --}}
                        <i class="nav-icon fa fa-window-maximize" aria-hidden="true"></i>
                        <p>
                            Invoice
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="{{ route('admin.invoice.all.index') }}"
                                class="nav-link {{ request()->routeIs('admin.invoice.all*') ? 'active' : '' }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    All Invoice
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.invoice.approved.invoice') }}"
                                class="nav-link {{ request()->routeIs('admin.invoice.approved.invoice') ? 'active' : '' }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Paid Invoice
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.invoice.pending.invoice') }}"
                                class="nav-link {{ request()->routeIs('admin.invoice.pending.invoice') ? 'active' : '' }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Pending Invoice
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.invoice.daily.invoice.form') }}"
                                class="nav-link {{ request()->routeIs('admin.invoice.daily.invoice*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Search Invoice Report</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li
                    class="nav-item {{ request()->routeIs('admin.stock.*') ? ' menu-is-opening menu-open active' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>
                            Stock
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.stock.report.index') }}"
                                class="nav-link {{ request()->routeIs('admin.stock.report*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Stock Report</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.stock.supplier.index') }}"
                                class="nav-link {{ request()->routeIs('admin.stock.supplier*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Supplier Report</p>
                            </a>
                        </li>
                    </ul>
                </li>



                <li class="nav-item">
                    <a href="{{ route('admin.site-setting.index') }}"
                        class="nav-link {{ request()->routeIs('admin.site-setting.*') ? 'active' : '' }}">
                        <i class="nav-icon nav-icon fas fa-th"></i>
                        <p>
                            Site Setting
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
