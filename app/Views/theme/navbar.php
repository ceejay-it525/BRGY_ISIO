<nav class="main-header navbar navbar-expand navbar-dark" id="mainNavbar" 
     style="background: linear-gradient(135deg, #1a3c6e, #2d6a9f); box-shadow: 0 2px 10px rgba(0,0,0,0.2); padding: 0 15px;">

    <!-- LEFT SIDE -->
    <ul class="navbar-nav align-items-center">
        <li class="nav-item">
            <a class="nav-link px-3" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars" style="color:#fff; font-size:16px;"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?= base_url('dashboard') ?>" class="nav-link d-flex align-items-center" style="color:#fff; gap:8px;">
                <i class="fas fa-home" style="font-size:14px;"></i>
                <span style="font-family:'Poppins',sans-serif; font-size:13px; font-weight:500;">Home</span>
            </a>
        </li>
    </ul>

    <!-- CENTER BRAND -->
    <div class="d-none d-md-flex align-items-center" style="position:absolute; left:50%; transform:translateX(-50%); gap:10px;">
        <div style="
            width: 30px; height: 30px;
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.4);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-landmark" style="color:#fff; font-size:13px;"></i>
        </div>
        <span style="font-family:'Poppins',sans-serif; font-size:14px; font-weight:700; color:#fff; letter-spacing:1px;">BMIS</span>
        <span style="font-family:'Poppins',sans-serif; font-size:11px; color:rgba(255,255,255,0.7); font-weight:400;">Barangay Management Information System</span>
    </div>

    <!-- RIGHT SIDE -->
    <ul class="navbar-nav ml-auto align-items-center" style="gap:4px;">

        <!-- Theme Toggle -->
        <li class="nav-item">
            <a class="nav-link px-2" href="#" id="themeToggle" title="Toggle Theme" style="color:#fff;">
                <i class="fas fa-sun" id="themeIcon" style="font-size:15px;"></i>
            </a>
        </li>

        <!-- Divider -->
        <li class="nav-item">
            <div style="width:1px; height:24px; background:rgba(255,255,255,0.3); margin:0 4px;"></div>
        </li>

        <!-- User Dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center" 
               href="#" id="userDropdown" 
               data-toggle="dropdown" 
               aria-haspopup="true" 
               aria-expanded="false"
               style="color:#fff; gap:8px; font-family:'Poppins',sans-serif;">

                <!-- Avatar Circle -->
                <div style="
                    width: 34px; height: 34px;
                    background: rgba(255,255,255,0.2);
                    border: 2px solid rgba(255,255,255,0.5);
                    border-radius: 50%;
                    display: flex; align-items: center; justify-content: center;
                    font-size: 13px; font-weight: 700; color:#fff;
                    font-family:'Poppins',sans-serif;">
                    <?= strtoupper(substr(session()->get('email'), 0, 1)) ?>
                </div>

                <!-- Email -->
                <div class="d-none d-md-block" style="line-height:1.3;">
                    <div style="font-size:12px; font-weight:600; color:#fff;">
                        <?= session()->get('name') ?? 'User' ?>
                    </div>
                    <div style="font-size:10px; color:rgba(255,255,255,0.7);">
                        <?= session()->get('email') ?>
                    </div>
                </div>
            </a>

            <!-- Dropdown Menu -->
            <div class="dropdown-menu dropdown-menu-right shadow-lg" 
                 aria-labelledby="userDropdown"
                 style="border:none; border-radius:14px; min-width:220px; padding:8px 0; margin-top:10px; overflow:hidden;">

                <!-- Header -->
                <div class="px-3 py-3" style="background:linear-gradient(135deg,#1a3c6e,#2d6a9f);">
                    <div class="d-flex align-items-center" style="gap:10px;">
                        <div style="
                            width:40px; height:40px;
                            background:rgba(255,255,255,0.2);
                            border:2px solid rgba(255,255,255,0.5);
                            border-radius:50%;
                            display:flex; align-items:center; justify-content:center;
                            font-size:16px; font-weight:700; color:#fff;
                            font-family:'Poppins',sans-serif;">
                            <?= strtoupper(substr(session()->get('email'), 0, 1)) ?>
                        </div>
                        <div>
                            <p class="mb-0" style="font-size:13px; font-weight:600; color:#fff; font-family:'Poppins',sans-serif;">
                                <?= session()->get('name') ?? 'User' ?>
                            </p>
                            <p class="mb-0" style="font-size:11px; color:rgba(255,255,255,0.75); font-family:'Poppins',sans-serif;">
                                <?= session()->get('email') ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Menu Items -->
                <div style="padding:6px 0;">
                    <a class="dropdown-item py-2 px-3 d-flex align-items-center" href="#" 
                       style="font-size:13px; font-family:'Poppins',sans-serif; gap:10px; color:#444;">
                        <div style="width:28px; height:28px; background:#e8f0fe; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                            <i class="fas fa-user-circle" style="color:#1a3c6e; font-size:13px;"></i>
                        </div>
                        My Profile
                    </a>
                    <a class="dropdown-item py-2 px-3 d-flex align-items-center" href="#" 
                       style="font-size:13px; font-family:'Poppins',sans-serif; gap:10px; color:#444;">
                        <div style="width:28px; height:28px; background:#f0f0f0; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                            <i class="fas fa-cog" style="color:#666; font-size:13px;"></i>
                        </div>
                        Settings
                    </a>
                </div>

                <div style="height:1px; background:#f0f0f0; margin:4px 0;"></div>

                <!-- Logout -->
                <div style="padding:6px 0;">
                    <a class="dropdown-item py-2 px-3 d-flex align-items-center" 
                       href="<?= base_url('/logout') ?>"
                       style="font-size:13px; font-family:'Poppins',sans-serif; gap:10px; color:#c0392b;">
                        <div style="width:28px; height:28px; background:#fff5f5; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                            <i class="fas fa-sign-out-alt" style="color:#c0392b; font-size:13px;"></i>
                        </div>
                        Logout
                    </a>
                </div>
            </div>
        </li>
    </ul>
</nav>

<style>
#mainNavbar .nav-link:hover {
    background: rgba(255,255,255,0.1);
    border-radius: 8px;
    transition: all 0.2s;
}
.dropdown-item:hover {
    background: #f0f4ff !important;
}
#themeToggle { transition: all 0.3s; }
#themeToggle:hover { transform: rotate(20deg); }
</style>

<script>
const themeToggle = document.getElementById('themeToggle');
const themeIcon   = document.getElementById('themeIcon');

if (localStorage.getItem('theme') === 'dark') {
    document.body.classList.add('dark-mode');
    themeIcon.classList.replace('fa-sun', 'fa-moon');
}

themeToggle.addEventListener('click', function(e) {
    e.preventDefault();
    document.body.classList.toggle('dark-mode');
    if (document.body.classList.contains('dark-mode')) {
        themeIcon.classList.replace('fa-sun', 'fa-moon');
        localStorage.setItem('theme', 'dark');
    } else {
        themeIcon.classList.replace('fa-moon', 'fa-sun');
        localStorage.setItem('theme', 'light');
    }
});
</script>