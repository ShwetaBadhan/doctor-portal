  <!-- Sidenav Menu Start -->
  <div class="sidebar" id="sidebar">

      <!-- Start Logo -->
      <div class="sidebar-logo">
          <div>
              <!-- Logo Normal -->
              <a href="{{ route('dashboard') }}" class="logo logo-normal">
                  <img src="assets/img/logo.svg" alt="Logo">
              </a>

              <!-- Logo Small -->
              <a href="{{ route('dashboard') }}" class="logo-small">
                  <img src="assets/img/logo-small.svg" alt="Logo">
              </a>

              <!-- Logo Dark -->
              <a href="{{ route('dashboard') }}" class="dark-logo">
                  <img src="assets/img/logo-white.svg" alt="Logo">
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
              <div class="sidebar-top shadow-sm p-2 rounded-1 mb-3 dropend">
                  <a href="javascript:void(0);" class="drop-arrow-none" data-bs-toggle="dropdown"
                      data-bs-auto-close="outside" data-bs-offset="0,22" aria-haspopup="false" aria-expanded="false">
                      <div class="d-flex justify-content-between align-items-center">
                          <div class="d-flex align-items-center">
                              <span class="avatar rounded-circle flex-shrink-0 p-2"><img
                                      src="assets/img/icons/trustcare.svg" alt="img"></span>
                              <div class="ms-2">
                                  <h6 class="fs-14 fw-semibold mb-0">Trustcare Clinic</h6>
                                  <p class="fs-13 mb-0">Lasvegas</p>
                              </div>
                          </div>
                          <i class="ti ti-arrows-transfer-up"></i>
                      </div>
                  </a>
                  <div class="dropdown-menu dropdown-menu-lg">
                      <div class="p-2">
                          <label class="dropdown-item d-flex align-items-center justify-content-between p-1">
                              <span class="d-flex align-items-center">
                                  <span class="me-2"><img src="assets/img/icons/clinic-01.svg" alt=""></span>
                                  <span class="fw-semibold text-dark">CureWell Medical Hub<small
                                          class="d-block text-muted fw-normal fs-13">Ohio</small></span>
                              </span>
                              <input class="form-check-input m-0 me-2" type="checkbox">
                          </label>
                          <label class="dropdown-item d-flex align-items-center justify-content-between p-1">
                              <span class="d-flex align-items-center">
                                  <span class="me-2"><img src="assets/img/icons/clinic-02.svg" alt=""></span>
                                  <span class="fw-semibold text-dark">Trustcare Clinic<small
                                          class="d-block text-muted fw-normal fs-13">Lasvegas</small></span>
                              </span>
                              <input class="form-check-input m-0 me-2" type="checkbox">
                          </label>
                          <label class="dropdown-item d-flex align-items-center justify-content-between p-1">
                              <span class="d-flex align-items-center">
                                  <span class="me-2"><img src="assets/img/icons/clinic-03.svg" alt=""></span>
                                  <span class="fw-semibold text-dark">NovaCare Medical<small
                                          class="d-block text-muted fw-normal fs-13">Washington</small></span>
                              </span>
                              <input class="form-check-input m-0 me-2" type="checkbox">
                          </label>
                          <label class="dropdown-item d-flex align-items-center justify-content-between p-1">
                              <span class="d-flex align-items-center">
                                  <span class="me-2"><img src="assets/img/icons/clinic-04.svg" alt=""></span>
                                  <span class="fw-semibold text-dark">Greeny Medical Clinic<small
                                          class="d-block text-muted fw-normal fs-13">Illinios</small></span>
                              </span>
                              <input class="form-check-input m-0 me-2" type="checkbox">
                          </label>
                      </div>
                  </div>
              </div>
              <ul>
                  <li class="menu-title"><span>Main Menu</span></li>
                  <li>
                      <ul>
                          <li class="submenu">
                              <a href="javascript:void(0);" class="active">
                                  <i class="ti ti-layout-dashboard"></i><span>Dashboard</span>

                              </a>

                          </li>


                      </ul>
                  </li>
                  <li class="menu-title"><span>Clinic</span></li>
                  <li>
                      <ul>
                          {{-- <li class="submenu">
                                    <a href="javascript:void(0);">
                                        <i class="ti ti-user-plus"></i><span>Doctors</span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <ul>
                                        <li><a href="{{ route('doctors') }}">Doctors</a></li>
                                        <li><a href="{{ route('create-doctor') }}">Create Doctor</a></li>
                                    </ul>
                                </li> --}}
                          <li class="submenu">
                              <a href="javascript:void(0);">
                                  <i class="ti ti-user-heart"></i><span>Patients</span>
                                  <span class="menu-arrow"></span>
                              </a>
                              <ul>
                                  <li><a href="{{ route('patients.index') }}">Patients</a></li>

                                  <li><a href="{{ route('patients.create') }}">Create Patient</a></li>
                              </ul>
                          </li>
                          <li class="submenu">
                              <a href="javascript:void(0);">
                                  <i class="ti ti-calendar-check"></i><span>Appointments</span>
                                  <span class="menu-arrow"></span>
                              </a>
                              <ul>
                                  <li><a href="{{ route('appointments.index') }}">Appointments</a></li>
                                  <li><a href="{{ route('appointments.create') }}">New Appointment</a></li>
                                  {{-- <li><a href="{{ route('appointment-calendar') }}">Calendar</a></li> --}}
                              </ul>
                          </li>
                          <!-- Medicines Menu -->
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
                                  <li>
                                      <a href="{{ route('medicine-groups.create') }}">
                                          Add New Group
                                      </a>
                                  </li>
                              </ul>
                          </li>

                      </ul>
                  </li>


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
                                  <li><a href="{{ route('roles.index') }}">Roles</a></li>
                                  <li><a href="{{ route('permissions.index') }}">Permissions</a></li>

                              </ul>
                          </li>

                      </ul>
                  </li>




                  {{-- <li class="menu-title"><span>Settings</span></li>
                        <li>
                            <ul>
                                <li class="submenu">
                                    <a href="javascript:void(0);">
                                        <i class="ti ti-user-cog"></i><span>Account Settings</span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <ul>
                                        <li><a href="profile-settings.html">Profile</a></li>
                                        <li><a href="security-settings.html">Security</a></li>
                                        <li><a href="notifications-settings.html">Notifications</a></li>
                                        <li><a href="integrations-settings.html">Integrations</a></li>
                                    </ul>
                                </li>
                                <li class="submenu">
                                    <a href="javascript:void(0);">
                                        <i class="ti ti-world-cog"></i><span>Website Settings</span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <ul>
                                        <li><a href="organization-settings.html">Organization</a></li>
                                        <li><a href="localization-settings.html">Localization</a></li>
                                        <li><a href="prefixes-settings.html">Prefixes</a></li>
                                        <li><a href="seo-setup-settings.html">SEO Setup</a></li>
                                        <li><a href="language-settings.html">Language</a></li>
                                        <li><a href="maintenance-mode-settings.html">Maintenance Mode</a></li>
                                        <li><a href="login-and-register-settings.html">Login & Register</a></li>
                                        <li><a href="preferences-settings.html">Preferences</a></li>
                                    </ul>
                                </li>
                                <li class="submenu">
                                    <a href="javascript:void(0);">
                                        <i class="ti ti-building-hospital"></i><span>Clinic Settings</span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <ul>
                                        <li><a href="appointment-settings.html">Appointment</a></li>
                                        <li><a href="working-hours-settings.html">Working Hours</a></li>
                                        <li><a href="cancellation-reason-settings.html">Cancellation Reason</a></li>
                                    </ul>
                                </li>
                                <li class="submenu">
                                    <a href="javascript:void(0);">
                                        <i class="ti ti-device-mobile-cog"></i><span>App Settings</span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <ul>
                                        <li><a href="invoice-settings.html">Invoice Settings</a></li>
                                        <li><a href="invoice-templates-settings.html">Invoice Templates</a></li>
                                        <li><a href="signatures-settings.html">Signatures</a></li>
                                        <li><a href="custom-fields-settings.html">Custom Fields</a></li>
                                    </ul>
                                </li>
                                <li class="submenu">
                                    <a href="javascript:void(0);">
                                        <i class="ti ti-device-desktop-cog"></i><span>System Settings</span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <ul>
                                        <li><a href="email-settings.html">Email Settings</a></li>
                                        <li><a href="email-templates-settings.html">Email Templates</a></li>
                                        <li><a href="sms-gateways-settings.html">SMS Gateways</a></li>
                                        <li><a href="sms-templates-settings.html">SMS Templates</a></li>
                                        <li><a href="gdpr-cookies-settings.html">GDPR Cookies</a></li>
                                    </ul>
                                </li>
                                <li class="submenu">
                                    <a href="javascript:void(0);">
                                        <i class="ti ti-settings-dollar"></i><span>Finance & Accounts</span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <ul>
                                        <li><a href="payment-methods-settings.html">Payment Methods</a></li>
                                        <li><a href="bank-accounts-settings.html">Bank Accounts</a></li>
                                        <li><a href="tax-rates-settings.html">Tax Rates</a></li>
                                        <li><a href="currencies-settings.html">Currencies</a></li>
                                    </ul>
                                </li>
                                <li class="submenu">
                                    <a href="javascript:void(0);">
                                        <i class="ti ti-settings-2"></i><span>Other Settings</span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <ul>
                                        <li><a href="sitemap-settings.html">Sitemap</a></li>
                                        <li><a href="clear-cache-settings.html">Clear Cache</a></li>
                                        <li><a href="storage-settings.html">Storage</a></li>
                                        <li><a href="cronjob-settings.html">Cronjob</a></li>
                                        <li><a href="ban-ip-address-settings.html">Ban IP Address</a></li>
                                        <li><a href="system-backup-settings.html">System Backup</a></li>
                                        <li><a href="database-backup-settings.html">Database Backup</a></li>
                                        <li><a href="system-update.html">System Update</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li> --}}

              </ul>
          </div>

      </div>

  </div>
  <!-- Sidenav Menu End -->
