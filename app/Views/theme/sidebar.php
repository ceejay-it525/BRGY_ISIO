<style type="text/css">
.nav-sidebar .nav-link {
    position: relative;
    transition: background 0.2s ease;
}

/* Orange left bar */
.nav-sidebar .nav-link::before {
    content: "";
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 4px;
    background: linear-gradient(to bottom, #ff6b35, #f7931e);
    border-radius: 0 3px 3px 0;
    transform: scaleY(0);
    transform-origin: top;
    transition: transform 0.25s ease;
}

/* Show orange bar on hover & active */
.nav-sidebar .nav-link.active::before,
.nav-sidebar .nav-link:hover::before {
    transform: scaleY(1);
}

/* SUPER LIGHT GRADIENT */
.nav-sidebar .nav-link:hover,
.nav-sidebar .nav-link.active {
    background: linear-gradient(
        to right,
        rgba(255, 107, 53, 0.08),
        rgba(247, 147, 30, 0.04)
    ) !important;
    box-shadow: none !important;
}

/* Submenu items same gradient */
.nav-treeview .nav-link:hover,
.nav-treeview .nav-link.active {
    background: linear-gradient(
        to right,
        rgba(255, 107, 53, 0.08),
        rgba(247, 147, 30, 0.04)
    ) !important;
}

/* Dark mode support */
body.dark-mode .main-sidebar .nav-link {
    color: #fff !important;
}

body.dark-mode .main-sidebar .nav-link p {
    color: #fff !important;
}

body.dark-mode .main-sidebar .nav-icon {
    color: #fff !important;
}

body.dark-mode .main-sidebar .nav-link.active,
body.dark-mode .main-sidebar .nav-link:hover {
    background-color: rgba(255, 255, 255, 0.12) !important;
}

/* Brand link glow */
.brand-link:hover {
    box-shadow: 0 0 20px rgba(255, 165, 0, 0.3) !important;
}
</style>

<aside class="main-sidebar sidebar-light-light sidebar-light elevation-5" id="mainSidebar">
    <!-- Brand Logo -->
    <div class="brand-link bg-gradient-warning" id="brandLink" style="cursor: default; border-bottom: 1px solid rgba(255,255,255,0.2);">
        <img src="<?= base_url('assets/adminlte/dist/img/AdminLTELogo.png') ?>" 
             alt="Barangay MIS Logo" 
             class="brand-image img-circle elevation-3" 
             style="opacity: .9">
        <span class="brand-text font-weight-light" style="color: white; font-size: 1.1rem;">
            <strong>BRGY ISIO</strong>
        </span>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="<?= base_url('dashboard') ?>" class="nav-link <?= (uri_string() == 'dashboard') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            <span>Dashboard</span>
                            <small class="d-block text-muted">Overview</small>
                        </p>
                    </a>
                </li>

                <!-- Activity Logs -->
                <li class="nav-item">
                    <a href="<?= base_url('logs') ?>" class="nav-link <?= (uri_string() == 'logs') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-history"></i>
                        <p>
                            <span>Activity Logs</span>
                            <small class="d-block text-muted">System audit</small>
                        </p>
                    </a>
                </li>

                <!-- USER ACCOUNTS - TOP LEVEL (PUT BACK) -->
                <li class="nav-item">
                    <a href="<?= base_url('users') ?>" class="nav-link <?= (uri_string() == 'users') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-user-lock text-danger"></i>
                        <p>
                            <span>User Accounts</span>
                            <small class="d-block text-muted">Manage users</small>
                        </p>
                    </a>
                </li>

                <!-- CORE BARANGAY MODULES -->
                <li class="nav-header text-warning">🏘️ CORE MODULES</li>

                <!-- Blotter System -->
                <li class="nav-item">
                    <a href="<?= base_url('blotter') ?>" class="nav-link <?= (uri_string() == 'blotter') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-file-alt text-danger"></i>
                        <p>
                            <span>Blotter System</span>
                            <small class="d-block text-muted">Complaints log</small>
                        </p>
                    </a>
                </li>

                <!-- Residents Management -->
                <li class="nav-item">
                    <a href="<?= base_url('residents') ?>" class="nav-link <?= (uri_string() == 'residents') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-users text-primary"></i>
                        <p>
                            <span>Residents</span>
                            <small class="d-block text-muted">Population registry</small>
                        </p>
                    </a>
                </li>

                <!-- Barangay Officials -->
                <li class="nav-item">
                    <a href="<?= base_url('barangay-officials') ?>" class="nav-link <?= (uri_string() == 'barangay-officials') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-user-tie text-warning"></i>
                        <p>
                            <span>Officials</span>
                            <small class="d-block text-muted">Elected officials</small>
                        </p>
                    </a>
                </li>

                <!-- Households -->
                <li class="nav-item">
                    <a href="<?= base_url('households') ?>" class="nav-link <?= (uri_string() == 'households') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-home text-success"></i>
                        <p>
                            <span>Households</span>
                            <small class="d-block text-muted">Family profiles</small>
                        </p>
                    </a>
                </li>

                <!-- SERVICES MODULES -->
                <li class="nav-header text-info">🏢 SERVICES</li>

                <!-- Clearances -->
                <li class="nav-item">
                    <a href="<?= base_url('clearances') ?>" class="nav-link <?= (uri_string() == 'clearances') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-certificate text-info"></i>
                        <p>
                            <span>Clearances</span>
                            <small class="d-block text-muted">Certificates</small>
                        </p>
                    </a>
                </li>

                <!-- Business Permits -->
                <li class="nav-item">
                    <a href="<?= base_url('business-permits') ?>" class="nav-link <?= (uri_string() == 'business-permits') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-store text-success"></i>
                        <p>
                            <span>Business Permits</span>
                            <small class="d-block text-muted">BPLS</small>
                        </p>
                    </a>
                </li>

                <!-- Indigents -->
                <li class="nav-item">
                    <a href="<?= base_url('indigents') ?>" class="nav-link <?= (uri_string() == 'indigents') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-hand-holding-heart text-purple"></i>
                        <p>
                            <span>Indigents</span>
                            <small class="d-block text-muted">Social services</small>
                        </p>
                    </a>
                </li>

                <!-- REPORTS & SETTINGS -->
                <li class="nav-header text-secondary">📊 UTILITIES</li>

                <!-- Reports -->
                <li class="nav-item">
                    <a href="<?= base_url('reports') ?>" class="nav-link <?= (uri_string() == 'reports') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-chart-bar text-secondary"></i>
                        <p>
                            <span>Reports</span>
                            <small class="d-block text-muted">Analytics</small>
                        </p>
                    </a>
                </li>

                <!-- Settings Dropdown -->
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                            <span>Settings</span>
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('settings') ?>" class="nav-link <?= (uri_string() == 'settings') ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>System Settings</p>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </nav>
    </div>
</aside>