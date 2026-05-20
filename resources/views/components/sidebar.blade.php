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
                        <!-- Dashboard -->
                        <li>
                            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
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
                        @php
                            $patientsActive = request()->routeIs('patients.*');
                        @endphp
                        <li class="submenu">
                            <a href="javascript:void(0);" class="{{ $patientsActive ? 'active' : '' }}">
                                <i class="ti ti-user-heart"></i><span>Patients</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul style="display: {{ $patientsActive ? 'block' : 'none' }};">
                                <li>
                                    <a href="{{ route('patients.index') }}" class="{{ request()->routeIs('patients.index') ? 'active' : '' }}">
                                       All Patients
                                    </a>
                                </li>
                                @can('create-patients')
                                <li>
                                    <a href="{{ route('patients.create') }}" class="{{ request()->routeIs('patients.create') ? 'active' : '' }}">
                                        Create Patient
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcan

                        <!-- Appointments Menu -->
                        @can('view-appointments')
                        @php
                            $appointmentsActive = request()->routeIs('appointments.*');
                        @endphp
                        <li class="submenu">
                            <a href="javascript:void(0);" class="{{ $appointmentsActive ? 'active' : '' }}">
                                <i class="ti ti-calendar-check"></i><span>Appointments</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul style="display: {{ $appointmentsActive ? 'block' : 'none' }};">
                                <li>
                                    <a href="{{ route('appointments.index') }}" class="{{ request()->routeIs('appointments.index') ? 'active' : '' }}">
                                       All Appointments
                                    </a>
                                </li>
                                @can('create-appointments')
                                <li>
                                    <a href="{{ route('appointments.create') }}" class="{{ request()->routeIs('appointments.create') ? 'active' : '' }}">
                                        New Appointment
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcan

                        <!-- Medicines Menu -->
                        @can('view-medicine-groups')
                        @php
                            $medicinesActive = request()->routeIs('medicine-groups.*');
                        @endphp
                        <li class="submenu">
                            <a href="javascript:void(0);" class="{{ $medicinesActive ? 'active' : '' }}">
                                <i class="ti ti-pill"></i>
                                <span>Medicines</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul style="display: {{ $medicinesActive ? 'block' : 'none' }};">
                                <li>
                                    <a href="{{ route('medicine-groups.index') }}" class="{{ request()->routeIs('medicine-groups.index') ? 'active' : '' }}">
                                        Medicine Groups
                                    </a>
                                </li>
                                @can('create-medicine-groups')
                                <li>
                                    <a href="{{ route('medicine-groups.create') }}" class="{{ request()->routeIs('medicine-groups.create') ? 'active' : '' }}">
                                        Add New Group
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcan

                        <!-- Invoices Menu -->
                        @can('view-invoices')
                        @php
                            $invoicesActive = request()->routeIs('invoices.*');
                        @endphp
                        <li class="submenu">
                            <a href="javascript:void(0);" class="{{ $invoicesActive ? 'active' : '' }}">
                                <i class="ti ti-file-invoice"></i>
                                <span>Invoices</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul style="display: {{ $invoicesActive ? 'block' : 'none' }};">
                                <li>
                                    <a href="{{ route('invoices.index') }}" class="{{ request()->routeIs('invoices.index') ? 'active' : '' }}">
                                      All  Invoices
                                    </a>
                                </li>
                                @can('create-invoices')
                                <li>
                                    <a href="{{ route('invoices.create') }}" class="{{ request()->routeIs('invoices.create') ? 'active' : '' }}">
                                        Create Invoice
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcan

                        <!-- Shipments Menu -->
                        @can('view-shipments')
                        @php
                            $shipmentsActive = request()->routeIs('shipments.*');
                        @endphp
                        <li class="submenu">
                            <a href="javascript:void(0);" class="{{ $shipmentsActive ? 'active' : '' }}">
                                <i class="ti ti-truck-delivery"></i>
                                <span>Shipments</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul style="display: {{ $shipmentsActive ? 'block' : 'none' }};">
                                <li>
                                    <a href="{{ route('shipments.index') }}" class="{{ request()->routeIs('shipments.index') ? 'active' : '' }}">
                                        Shipments List
                                    </a>
                                </li>
                                @can('create-shipments')
                                <li>
                                    <a href="{{ route('shipments.create') }}" class="{{ request()->routeIs('shipments.create') ? 'active' : '' }}">
                                        Create
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
                @php
                    $adminActive = request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('permissions.*');
                @endphp
                <li class="menu-title"><span>Administration</span></li>
                <li>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);" class="{{ $adminActive ? 'active' : '' }}">
                                <i class="ti ti-user"></i><span>Users</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul style="display: {{ $adminActive ? 'block' : 'none' }};">
                                <li>
                                    <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                                        Users
                                    </a>
                                </li>
                                @can('view-roles')
                                <li>
                                    <a href="{{ route('roles.index') }}" class="{{ request()->routeIs('roles.*') ? 'active' : '' }}">
                                        Roles
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('permissions.index') }}" class="{{ request()->routeIs('permissions.*') ? 'active' : '' }}">
                                        Permissions
                                    </a>
                                </li>
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