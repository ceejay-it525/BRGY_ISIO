<style>
/* ============================================================
   BMIS SIDEBAR — Plus Jakarta Sans, dark navy + orange accent
   ============================================================ */
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

/* ── Base sidebar ────────────────────────────────────── */
.main-sidebar {
    background: #0f172a !important;
    border-right: none !important;
    box-shadow: 4px 0 24px rgba(0,0,0,0.3) !important;
    transition: width 0.25s ease;
    font-family: 'Plus Jakarta Sans', sans-serif !important;
}

/* ── Brand strip ─────────────────────────────────────── */
.brand-link {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;
    border-bottom: 1px solid rgba(255,255,255,0.06) !important;
    padding: 16px 18px !important;
    display: flex !important;
    align-items: center !important;
    gap: 12px;
    text-decoration: none !important;
    transition: background 0.2s;
}
.brand-link:hover {
    background: linear-gradient(135deg, #263348 0%, #1a2640 100%) !important;
}
.brand-image {
    width: 38px !important;
    height: 38px !important;
    border-radius: 10px !important;
    object-fit: cover;
    border: 2px solid rgba(249,115,22,0.5) !important;
    box-shadow: 0 0 12px rgba(249,115,22,0.3);
    opacity: 1 !important;
    flex-shrink: 0;
    transition: transform 0.3s ease;
}
.brand-link:hover .brand-image { transform: rotate(5deg) scale(1.05); }
.brand-text {
    display: flex;
    flex-direction: column;
    line-height: 1.2;
}
.brand-text strong {
    font-size: 14px !important;
    font-weight: 800 !important;
    color: #ffffff !important;
    letter-spacing: 0.3px;
}
.brand-text small {
    font-size: 10px !important;
    color: #64748b !important;
    font-weight: 500;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

/* ── Sidebar scroll container ────────────────────────── */
.sidebar {
    background: transparent;
    overflow-y: auto;
    overflow-x: hidden;
    scrollbar-width: thin;
    scrollbar-color: #1e293b transparent;
}
.sidebar::-webkit-scrollbar { width: 4px; }
.sidebar::-webkit-scrollbar-track { background: transparent; }
.sidebar::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 2px; }

/* ── Nav container ───────────────────────────────────── */
.nav-sidebar { padding: 8px 0 24px; }

/* ── Section Headers ─────────────────────────────────── */
.nav-header {
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    font-size: 10px !important;
    font-weight: 700 !important;
    letter-spacing: 1.2px !important;
    text-transform: uppercase !important;
    padding: 18px 20px 6px !important;
    color: #475569 !important;
    display: flex;
    align-items: center;
    gap: 6px;
}
.nav-header::after {
    content: '';
    flex: 1;
    height: 1px;
    background: rgba(255,255,255,0.05);
    margin-left: 6px;
}

/* ── Nav Items ───────────────────────────────────────── */
.nav-sidebar .nav-item {
    margin: 1px 10px;
}
.nav-sidebar > .nav-item {
    margin: 2px 10px;
}

.nav-sidebar .nav-link {
    position: relative;
    display: flex !important;
    align-items: center !important;
    padding: 10px 14px !important;
    border-radius: 10px !important;
    color: #94a3b8 !important;
    font-size: 13px !important;
    font-weight: 500 !important;
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    transition: all 0.22s cubic-bezier(0.4,0,0.2,1);
    overflow: hidden;
    gap: 10px;
    text-decoration: none !important;
}

/* left accent bar */
.nav-sidebar .nav-link::before {
    content: '';
    position: absolute;
    left: 0; top: 50%;
    transform: translateY(-50%) scaleY(0);
    width: 3px;
    height: 60%;
    background: linear-gradient(180deg, #f97316, #fb923c);
    border-radius: 0 3px 3px 0;
    transition: transform 0.22s cubic-bezier(0.4,0,0.2,1);
}

.nav-sidebar .nav-link:hover,
.nav-sidebar .nav-link.active {
    background: rgba(255,255,255,0.06) !important;
    color: #f1f5f9 !important;
    transform: translateX(2px);
}
.nav-sidebar .nav-link:hover::before,
.nav-sidebar .nav-link.active::before {
    transform: translateY(-50%) scaleY(1);
}
.nav-sidebar .nav-link.active {
    background: rgba(249,115,22,0.12) !important;
    color: #fff !important;
    font-weight: 600 !important;
    box-shadow: inset 0 0 0 1px rgba(249,115,22,0.2);
}

/* ── Nav Icons ───────────────────────────────────────── */
.nav-sidebar .nav-link .nav-icon {
    width: 32px !important;
    height: 32px !important;
    border-radius: 8px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 14px !important;
    flex-shrink: 0;
    background: rgba(255,255,255,0.05);
    color: #64748b !important;
    transition: all 0.22s ease;
    margin-right: 0 !important;
}
.nav-sidebar .nav-link:hover .nav-icon,
.nav-sidebar .nav-link.active .nav-icon {
    background: rgba(249,115,22,0.15) !important;
    color: #fb923c !important;
}

/* icon color overrides (for non-active state) */
.nav-sidebar .nav-link .nav-icon.text-danger  { color: #f87171 !important; }
.nav-sidebar .nav-link .nav-icon.text-primary { color: #60a5fa !important; }
.nav-sidebar .nav-link .nav-icon.text-warning { color: #fbbf24 !important; }
.nav-sidebar .nav-link .nav-icon.text-success { color: #4ade80 !important; }
.nav-sidebar .nav-link .nav-icon.text-info    { color: #38bdf8 !important; }
.nav-sidebar .nav-link .nav-icon.text-purple  { color: #c084fc !important; }

/* ── Nav link text block ─────────────────────────────── */
.nav-sidebar .nav-link p {
    margin: 0 !important;
    display: flex !important;
    flex-direction: column !important;
    line-height: 1.2;
    flex: 1;
}
.nav-sidebar .nav-link p span,
.nav-sidebar .nav-link p:not(:has(small)) {
    font-size: 13px;
    font-weight: 500;
    color: inherit;
}
.nav-sidebar .nav-link p small {
    font-size: 10px !important;
    color: #475569 !important;
    font-weight: 400;
    margin-top: 1px;
}
.nav-sidebar .nav-link:hover p small,
.nav-sidebar .nav-link.active p small {
    color: #94a3b8 !important;
}

/* ── Treeview arrow ──────────────────────────────────── */
.nav-sidebar .nav-link .right {
    font-size: 11px !important;
    color: #475569 !important;
    margin-left: auto;
    transition: transform 0.22s ease;
}
.nav-sidebar .nav-item.menu-open > .nav-link .right {
    transform: rotate(-90deg);
    color: #fb923c !important;
}

/* ── Sub-menu ────────────────────────────────────────── */
.nav-treeview {
    background: rgba(0,0,0,0.15);
    border-radius: 8px;
    margin: 2px 4px;
    padding: 4px 0;
}
.nav-treeview .nav-item { margin: 1px 6px !important; }
.nav-treeview .nav-link {
    padding: 8px 12px !important;
    font-size: 12px !important;
    border-radius: 8px !important;
}
.nav-treeview .nav-link .nav-icon {
    width: 22px !important; height: 22px !important;
    background: transparent !important;
    font-size: 8px !important;
    color: #475569 !important;
}
.nav-treeview .nav-link:hover .nav-icon,
.nav-treeview .nav-link.active .nav-icon {
    background: transparent !important;
    color: #fb923c !important;
}

/* ── User panel (bottom) ─────────────────────────────── */
.sidebar-user-panel {
    margin: 0 10px 8px;
    padding: 12px 14px;
    background: rgba(255,255,255,0.04);
    border-radius: 10px;
    border: 1px solid rgba(255,255,255,0.06);
    display: flex;
    align-items: center;
    gap: 10px;
}
.sidebar-user-panel .user-avatar {
    width: 34px; height: 34px; border-radius: 50%;
    background: linear-gradient(135deg, #f97316, #fb923c);
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; color: #fff; font-weight: 700; flex-shrink: 0;
}
.sidebar-user-panel .user-info .user-name {
    font-size: 12px; font-weight: 700; color: #e2e8f0; line-height: 1.2;
}
.sidebar-user-panel .user-info .user-role {
    font-size: 10px; color: #475569; font-weight: 500;
}

/* ── Responsive ──────────────────────────────────────── */
@media (max-width: 768px) {
    .nav-sidebar > .nav-item { margin: 1px 6px; }
    .nav-sidebar .nav-link { padding: 9px 12px !important; }
}
</style>

<aside class="main-sidebar elevation-4" id="mainSidebar">

    <!-- Brand -->
    <a href="<?= base_url('dashboard') ?>" class="brand-link">
        <img src="<?= base_url('assets/img/isio.jpeg.png') ?>"
             alt="Barangay Isio Logo"
             class="brand-image">
        <span class="brand-text">
            <strong>BRGY ISIO</strong>
            <small>Management System</small>
        </span>
    </a>

    <div class="sidebar">
        <!-- Optional user panel -->
        <div class="sidebar-user-panel mt-2">
            <div class="user-avatar"><?= strtoupper(substr(session('fullname') ?? 'A', 0, 1)) ?></div>
            <div class="user-info">
                <div class="user-name"><?= esc(session('fullname') ?? 'Administrator') ?></div>
                <div class="user-role"><?= esc(session('role') ?? 'System Admin') ?></div>
            </div>
        </div>

        <nav class="mt-1">
            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview" role="menu" data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="<?= base_url('dashboard') ?>"
                       class="nav-link <?= (uri_string() == 'dashboard') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            <span>Dashboard</span>
                            <small>Overview</small>
                        </p>
                    </a>
                </li>

                <!-- Activity Logs -->
                <li class="nav-item">
                    <a href="<?= base_url('log') ?>"
                       class="nav-link <?= is_active(1, 'log') ?>">
                        <i class="nav-icon fas fa-history"></i>
                        <p>
                            <span>Activity Logs</span>
                            
                        </p>
                    </a>
                </li>

                <!-- User Accounts -->
                <li class="nav-item">
                    <a href="<?= base_url('users') ?>"
                       class="nav-link <?= (uri_string() == 'users') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-user-lock text-danger"></i>
                        <p>
                            <span>User Accounts</span>
                            <small>Manage users</small>
                        </p>
                    </a>
                </li>

                <!-- ── CORE MODULES ─────────────────── -->
                <li class="nav-header">Core Modules</li>

                <li class="nav-item">
                    <a href="<?= base_url('blotter') ?>"
                       class="nav-link <?= (uri_string() == 'blotter') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-file-alt text-danger"></i>
                        <p>
                            <span>Blotter</span>
                            <small>Complaints log</small>
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('residents') ?>"
                       class="nav-link <?= (uri_string() == 'residents') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-users text-primary"></i>
                        <p>
                            <span>Residents</span>
                            <small>Population registry</small>
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('households') ?>"
                       class="nav-link <?= (uri_string() == 'households') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-home text-success"></i>
                        <p>
                            <span>Households</span>
                            <small>Family profiles</small>
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('barangay-officials') ?>"
                       class="nav-link <?= (uri_string() == 'barangay-officials') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-user-tie text-warning"></i>
                        <p>
                            <span>Officials</span>
                            <small>Elected officials</small>
                        </p>
                    </a>
                </li>

                <!-- ── SERVICES ─────────────────────── -->
                <li class="nav-header">Services</li>

                <li class="nav-item">
                    <a href="<?= base_url('clearances') ?>"
                       class="nav-link <?= (uri_string() == 'clearances') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-certificate text-info"></i>
                        <p>
                            <span>Clearances</span>
                            <small>Issue certificates</small>
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('permits') ?>"
                       class="nav-link <?= (uri_string() == 'permits') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-store text-success"></i>
                        <p>
                            <span>Permits</span>
                            <small>Business (BPLS)</small>
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('indigents') ?>"
                       class="nav-link <?= (uri_string() == 'indigents') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-hand-holding-heart text-purple"></i>
                        <p>
                            <span>Indigents</span>
                            <small>Social services</small>
                        </p>
                    </a>
                </li>

                <!-- ── UTILITIES ────────────────────── -->
                <li class="nav-header">Utilities</li>

                <li class="nav-item">
                    <a href="<?= base_url('events') ?>"
                       class="nav-link <?= (uri_string() == 'events') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-calendar-alt text-warning"></i>
                        <p>
                            <span>Events</span>
                            <small>Barangay calendar</small>
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('reports') ?>"
                       class="nav-link <?= (uri_string() == 'reports') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>
                            <span>Reports</span>
                            <small>Analytics &amp; exports</small>
                        </p>
                    </a>
                </li>

                <li class="nav-item has-treeview
                    <?= in_array(uri_string(), ['settings']) ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link
                        <?= (uri_string() == 'settings') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                            <span>Settings</span>
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('settings') ?>"
                               class="nav-link <?= (uri_string() == 'settings') ? 'active' : '' ?>">
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