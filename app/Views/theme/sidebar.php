<style type="text/css">
/* BASE SIDEBAR STYLES */
.main-sidebar {
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.sidebar-light-light {
    background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
    border-right: 1px solid rgba(0,0,0,0.05);
    box-shadow: 4px 0 20px rgba(0,0,0,0.08);
}

body.dark-mode .sidebar-light-light {
    background: linear-gradient(145deg, #1a1a2e 0%, #16213e 100%);
    border-right: 1px solid rgba(255,255,255,0.1);
    box-shadow: 4px 0 25px rgba(0,0,0,0.4);
}

/* NAV LINKS - LIGHT MODE */
.nav-sidebar .nav-link {
    position: relative;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    color: #4a5568 !important;
    font-weight: 500;
    font-family: 'Poppins', sans-serif;
    padding: 12px 20px !important;
    border-radius: 0 12px 12px 0;
    margin: 2px 8px;
    overflow: hidden;
}

.nav-sidebar .nav-link p {
    color: inherit !important;
    font-size: 14px;
    margin: 0;
}

.nav-sidebar .nav-link .nav-icon {
    color: #6b7280 !important;
    font-size: 18px;
    width: 24px;
    margin-right: 12px;
}

.nav-sidebar .nav-link small {
    font-size: 11px !important;
    opacity: 0.7;
    font-weight: 400;
}

/* ORANGE ACCENT BAR */
.nav-sidebar .nav-link::before {
    content: "";
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 4px;
    background: linear-gradient(135deg, #ff6b35, #f7931e, #f57c00);
    border-radius: 0 3px 3px 0;
    transform: scaleY(0) translateX(-2px);
    transform-origin: top;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 0 10px rgba(255, 107, 53, 0.4);
}

.nav-sidebar .nav-link:hover::before,
.nav-sidebar .nav-link.active::before {
    transform: scaleY(1) translateX(0);
}

/* HOVER & ACTIVE STATES - LIGHT MODE */
.nav-sidebar .nav-link:hover,
.nav-sidebar .nav-link.active {
    background: linear-gradient(90deg, 
        rgba(255, 107, 53, 0.12), 
        rgba(247, 147, 30, 0.08),
        rgba(255, 107, 53, 0.06)
    ) !important;
    color: #1a3c6e !important;
    transform: translateX(4px);
    box-shadow: 0 8px 25px rgba(255, 107, 53, 0.15) !important;
}

.nav-sidebar .nav-link:hover .nav-icon,
.nav-sidebar .nav-link.active .nav-icon {
    color: #ff6b35 !important;
    transform: scale(1.1);
}

/* DARK MODE TEXT & ICONS */
body.dark-mode .nav-sidebar .nav-link {
    color: #e2e8f0 !important;
}

body.dark-mode .nav-sidebar .nav-link .nav-icon {
    color: #94a3b8 !important;
}

body.dark-mode .nav-sidebar .nav-link:hover,
body.dark-mode .nav-sidebar .nav-link.active {
    background: linear-gradient(90deg, 
        rgba(255, 107, 53, 0.20), 
        rgba(247, 147, 30, 0.15),
        rgba(255, 107, 53, 0.10)
    ) !important;
    color: #ffffff !important;
    box-shadow: 0 8px 30px rgba(255, 107, 53, 0.3) !important;
}

body.dark-mode .nav-sidebar .nav-link:hover .nav-icon,
body.dark-mode .nav-sidebar .nav-link.active .nav-icon {
    color: #ffd689 !important;
}

/* NAV HEADERS */
.nav-header {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    font-size: 11px;
    letter-spacing: 1px;
    padding: 12px 20px 8px !important;
    margin: 0 8px 4px;
    text-transform: uppercase;
}

body.dark-mode .nav-header {
    color: #94a3b8 !important;
}

.nav-header.text-warning { color: #f59e0b !important; }
.nav-header.text-info { color: #0ea5e9 !important; }
.nav-header.text-secondary { color: #64748b !important; }

/* BRAND LINK IMPROVEMENTS */
.brand-link {
    background: linear-gradient(135deg, #ff6b35, #f7931e) !important;
    border-bottom: 3px solid rgba(255,255,255,0.2) !important;
    padding: 16px 20px !important;
    transition: all 0.3s ease;
    cursor: default;
}

.brand-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(255, 107, 53, 0.4) !important;
}

.brand-text {
    font-family: 'Poppins', sans-serif !important;
    font-weight: 700 !important;
    letter-spacing: 1px;
}

.brand-image {
    width: 36px !important;
    height: 36px !important;
    opacity: 1 !important;
    transition: all 0.3s ease;
}

.brand-link:hover .brand-image {
    transform: scale(1.1) rotate(5deg);
}

/* SUBMENU STYLES */
.nav-treeview .nav-link {
    padding-left: 50px !important;
    margin: 1px 8px !important;
    font-size: 13px !important;
    border-radius: 0 8px 8px 0 !important;
}

.nav-treeview .nav-link:hover,
.nav-treeview .nav-link.active {
    background: linear-gradient(90deg, rgba(255, 107, 53, 0.10), rgba(247, 147, 30, 0.06)) !important;
    transform: translateX(2px);
}

body.dark-mode .nav-treeview .nav-link:hover,
body.dark-mode .nav-treeview .nav-link.active {
    background: linear-gradient(90deg, rgba(255, 107, 53, 0.18), rgba(247, 147, 30, 0.12)) !important;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .nav-sidebar .nav-link {
        margin: 1px 4px !important;
        padding: 10px 16px !important;
    }
    
    .brand-link {
        padding: 12px 16px !important;
    }
}
</style>

<aside class="main-sidebar sidebar-light-light sidebar-light elevation-5" id="mainSidebar">
    <!-- Brand Logo -->
    <div class="brand-link" id="brandLink">
        <img src="<?= base_url('assets/adminlte/dist/img/AdminLTELogo.png') ?>" 
             alt="Barangay MIS Logo" 
             class="brand-image img-circle elevation-3">
        <span class="brand-text font-weight-light">
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
                    <a href="<?= base_url('log') ?>" class="nav-link <?= is_active(1, 'log') ?>">
                        <i class="nav-icon fas fa-history"></i>
                        <p>Activity Logs</p>
                    </a>
                </li>

                <!-- User Accounts -->
                <li class="nav-item">
                    <a href="<?= base_url('users') ?>" class="nav-link <?= (uri_string() == 'users') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-user-lock text-danger"></i>
                        <p>
                            <span>User Accounts</span>
                            <small class="d-block text-muted">Manage users</small>
                        </p>
                    </a>
                </li>

                <!-- CORE MODULES -->
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

                <!-- Residents -->
                <li class="nav-item">
                    <a href="<?= base_url('residents') ?>" class="nav-link <?= (uri_string() == 'residents') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-users text-primary"></i>
                        <p>
                            <span>Residents</span>
                            <small class="d-block text-muted">Population registry</small>
                        </p>
                    </a>
                </li>

                <!-- Officials -->
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

                <!-- SERVICES -->
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
                    <a href="<?= base_url('permits') ?>" class="nav-link <?= (uri_string() == 'business-permits') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-store text-success"></i>
                        <p>
                            <span>Permits</span>
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

                <!-- UTILITIES -->
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

                <!-- Settings -->
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