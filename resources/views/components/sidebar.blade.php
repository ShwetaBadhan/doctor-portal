<!-- Sidenav Menu Start -->
<div class="sidebar" id="sidebar">

    <!-- Start Logo -->
    <div class="sidebar-logo">
        <div>
            <!-- Logo Normal -->
            <a href="{{ route('dashboard') }}" class="logo logo-normal">
                <img src="{{ url('assets/img/logo.jpeg')}}" alt="Logo">
            </a>

            <!-- Logo Small -->
            <a href="{{ route('dashboard') }}" class="logo-small">
                <img src="{{ url('assets/img/logo.jpeg')}}" alt="Logo">
            </a>

            <!-- Logo Dark -->
            <a href="{{ route('dashboard') }}" class="dark-logo">
                <img src="{{ url('assets/img/logo.jpeg')}}" alt="Logo">
            </a>
        </div>
        <button class="sidenav-toggle-btn btn border-0 p-0 active" id="toggle_btn">
            <i class="ti ti-arrow-left text-body"></i>
        </button>

        <!-- Sidebar Menu Close -->
        <button class="sidebar-close">
            <i class="ti ti-x align-middle"></i>
        </button>
    </div>
    <!-- End Logo -->

    <!-- Sidenav Menu -->
    <div class="sidebar-inner" data-simplebar="">
        <div id="sidebar-menu" class="sidebar-menu">
            
            <ul>
                <li class="menu-title"><span>Main Menu</span></li>
                <li>
                    <ul>
                        <li class="submenu">
                            <a href="{{ route('dashboard') }}" class="active">
                                <i class="ti ti-layout-dashboard"></i><span>Dashboard</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li class="menu-title"><span>Clinic</span></li>
                <li>
                    <ul>
                        <!-- Patients Menu -->
                        @can('view-patients')
                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <i class="ti ti-user-heart"></i><span>Patients</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('patients.index') }}">Patients</a></li>
                                @can('create-patients')
                                <li><a href="{{ route('patients.create') }}">Create Patient</a></li>
                                @endcan
                            </ul>
                        </li>
                        @endcan

                        <!-- Appointments Menu -->
                        @can('view-appointments')
                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <i class="ti ti-calendar-check"></i><span>Appointments</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('appointments.index') }}">Appointments</a></li>
                                @can('create-appointments')
                                <li><a href="{{ route('appointments.create') }}">New Appointment</a></li>
                                @endcan
                            </ul>
                        </li>
                        @endcan

                        <!-- Medicines Menu -->
                        @can('view-medicine-groups')
                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <i class="ti ti-pill"></i>
                                <span>Medicines</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul style="display: none;">
                                <li>
                                    <a href="{{ route('medicine-groups.index') }}">
                                        Medicine Groups
                                    </a>
                                </li>
                                @can('create-medicine-groups')
                                <li>
                                    <a href="{{ route('medicine-groups.create') }}">
                                        Add New Group
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcan

                        <!-- Invoices Menu -->
                        @can('view-invoices')
                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <i class="ti ti-file-invoice"></i>
                                <span>Invoices</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul style="display: none;">
                                <li>
                                    <a href="{{ route('invoices.index') }}">
                                        Invoices
                                    </a>
                                </li>
                                @can('create-invoices')
                                <li>
                                    <a href="{{ route('invoices.create') }}">
                                        Create Invoice
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcan

                        <!-- Shipments Menu -->
                        @can('view-shipments')
                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <i class="ti ti-truck-delivery"></i>
                                <span>Shipments</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul style="display: none;">
                                <li>
                                    <a href="{{ route('shipments.index') }}">
                                        Shipments
                                    </a>
                                </li>
                                @can('create-shipments')
                                <li>
                                    <a href="{{ route('shipments.create') }}">
                                        Create Shipment
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcan

                    </ul>
                </li>

                <!-- Administration Menu (Only for Admin/Super Admin) -->
                @can('view-users')
                <li class="menu-title"><span>Administration</span></li>
                <li>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <i class="ti ti-user"></i><span>Users</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('users.index') }}">Users</a></li>
                                @can('view-roles')
                                <li><a href="{{ route('roles.index') }}">Roles</a></li>
                                <li><a href="{{ route('permissions.index') }}">Permissions</a></li>
                                @endcan
                            </ul>
                        </li>
                    </ul>
                </li>
                @endcan

            </ul>
        </div>
    </div>
</div>
<!-- Sidenav Menu End -->